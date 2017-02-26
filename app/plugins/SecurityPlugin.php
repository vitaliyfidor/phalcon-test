<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    /**
     * Returns an existing or new access control list
     *
     * @returns AclList
     */
    public function getAcl()
    {
        //Check whether acl data already exist
        if (!is_file(APP_PATH . "/app/security/acl.data") || 0 == filesize(APP_PATH . "/app/security/acl.data")) {
            //if (!isset($this->persistent->acl)) {
            $acl = new AclList();
            $acl->setDefaultAction(Acl::DENY);
            // Register roles
            $roles = [
                'admins'  => new Role(
                    'admin',
                    'Admin privileges, granted after sign in.'
                ),
                'users'  => new Role(
                    'user',
                    'Member privileges, granted after sign in.'
                ),
                'guests' => new Role(
                    'guest',
                    'Anyone browsing the site who is not signed in is considered to be a "Guest".'
                )
            ];
            foreach ($roles as $role) {
                $acl->addRole($role);
            }
            //Private area resources
            $privateResources = [
                'user'    => ['index', 'search', 'new', 'edit', 'save', 'create', 'delete']
            ];
            foreach ($privateResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }
            //Public area resources
            $publicResources = [
                'errors'     => ['show401', 'show404', 'show500'],
                'signup'    => ['index', 'register', 'login', 'logout']
            ];
            foreach ($publicResources as $resource => $actions) {
                $acl->addResource(new Resource($resource), $actions);
            }
            //Grant access to public areas to both users and guests
            foreach ($roles as $role) {
                foreach ($publicResources as $resource => $actions) {
                    foreach ($actions as $action){
                        $acl->allow($role->getName(), $resource, $action);
                    }
                }
            }
            //Grant access to private area to role Users
            foreach ($privateResources as $resource => $actions) {
                foreach ($actions as $action){
                    $acl->allow('user', $resource, $action);
                    $acl->allow('admin', $resource, $action);
                }
            }
            //The acl is stored in session, APC would be useful here too
            //$this->persistent->acl = $acl;

            // Store serialized list into plain file
            file_put_contents(APP_PATH . "/app/security/acl.data", serialize($acl));
        } else {
            //Restore acl object from serialized file
            $acl = unserialize(file_get_contents(APP_PATH . "/app/security/acl.data"));
        }
        //return $this->persistent->acl;
        return $acl;
    }
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('auth');
        $role = 'guest';
        if ($auth) {
            $role = $auth['role'];
        }
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $acl = $this->getAcl();
        if (!$acl->isResource($controller)) {
            $dispatcher->forward([
                'controller' => 'errors',
                'action'     => 'show404'
            ]);
            return false;
        }
        $allowed = $acl->isAllowed($role, $controller, $action);
        if (!$allowed) {
            $this->flash->error('Login first, please.');
            $dispatcher->forward([
                'controller' => 'signup',
                'action'     => 'login'
            ]);
            $this->session->destroy();
            return false;
        }
    }
}
