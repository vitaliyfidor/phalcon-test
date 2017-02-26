<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserController extends Controller
{
    /**
     * Shows the index and search actions with pagination
     */
    public function indexAction()
    {
        $this->view->form = new SearchForm;

        $numberPage = 1;
        if ($this->request->isPost()) {
            $this->persistent->searchParams = $this->request->getPost('search', ['striptags', 'trim', 'string'], null);
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }
        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }
        $user = new Users;
        $users = $user->findUsersWithGroupConcatSql($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any products");
        }

        $paginator = new Paginator([
            "data"  => $users,
            "limit" => 5,
            "page"  => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();

    }

    /**
     * Edits a product based on its id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user = Users::findFirstById($id);
            if (!$user) {
                $this->flash->error("Product was not found");
                return $this->dispatcher->forward(
                    [
                        "controller" => "user",
                        "action"     => "index",
                    ]
                );
            }
            $this->view->form = new UsersForm($user, ['edit' => true]);
        }
    }

    /**
     * Saves current product in screen
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "user",
                    "action"     => "index",
                ]
            );
        }
        $id = $this->request->getPost("id", "int");
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User does not exist");
            return $this->dispatcher->forward(
                [
                    "controller" => "user",
                    "action"     => "index",
                ]
            );
        }
        $form = new UsersForm($user, ['edit' => true]);
        $this->view->form = $form;
        $data = $this->request->getPost();
        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(
                [
                    "controller" => "user",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $user->UsersGroups->delete();
        $groups = $this->request->getPost('groupsIds');
        $newGroups = Groups::find(
            [
                'id IN ({id:array})',
                'bind' => [
                    'id' => $groups
                ]
            ]
        );
        $usersGroups = [];

        foreach ($newGroups as $group) {
            $newRelation = new UsersGroups();
            $newRelation->groups_id = $group->id;
            $usersGroups[] = $newRelation;
        }
        $user->UsersGroups = $usersGroups;

        if ($user->update() == false) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(
                [
                    "controller" => "user",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }
        $form->clear();
        $this->flash->success("User was updated successfully");
        return $this->dispatcher->forward(
            [
                "controller" => "user",
                "action"     => "index",
            ]
        );
    }

    /**
     * Deletes a product
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
        } elseif (!$user->delete()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }
        } else {
            $user->UsersGroups->delete();
            $this->flash->success( "User was deleted" );
        }

        return $this->dispatcher->forward(
            [
                "controller" => "user",
                "action"     => "index",
            ]
        );
    }
}