<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function registerAction()
    {
        $form = new UsersForm;
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username', 'alphanum');
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');
            $groups = $this->request->getPost('groupsIds');
            $repeatPassword = $this->request->getPost('repeatPassword');
            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are different');
            } else {
                $user = new Users();
                $user->username = $username;
                $user->password = sha1( $password );
                $user->email = $email;
                $user->role = $role;
                $usersGroups = [];
                foreach ( $groups as $groupId ) {
                    $newRelation = Groups::findFirstById( $groupId );
                    $usersGroups[] = $newRelation;
                }
                $user->relatedGroups = $usersGroups;
                if ( $user->create() == false ) {
                    foreach ( $user->getMessages() as $message ) {
                        $this->flash->error( (string)$message );
                    }
                } else {
                    $this->tag->setDefault( 'email', '' );
                    $this->tag->setDefault( 'password', '' );
                    $auth = $this->session->get('auth');
                    if($auth) {
                        $this->flash->success( 'New user was registered.' );
                        return $this->dispatcher->forward(
                            [
                                "controller" => "user",
                                "action" => "index",
                            ]
                        );
                    }
                    $this->flash->success( 'Thanks for sign-up, please log-in to start' );
                    return $this->dispatcher->forward(
                        [
                            "controller" => "signup",
                            "action" => "login",
                        ]
                    );
                }
            }
        }
        $this->view->form = $form;
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(Users $user)
    {
        $this->session->set('auth', [
            'id' => $user->id,
            'name' => $user->username,
            'role' => $user->getRoles()->name
        ]);
    }
    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $user = Users::findFirst([
                "username = :username: AND password = :password:",
                'bind' => ['username' => $username, 'password' => sha1($password)]
            ]);
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->username);
                return $this->dispatcher->forward(
                    [
                        "controller" => "user",
                        "action"     => "index",
                    ]
                );
            }
            $this->flash->error('Wrong username/password');
        }
        $this->view->form = new LoginForm();
    }
    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->dispatcher->forward(
            [
                "controller" => "signup",
                "action"     => "login",
            ]
        );
    }
}