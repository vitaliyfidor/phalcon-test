<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Relation;
use Phalcon\Mvc\Model\Manager as ModelsManager;

class Users extends Model
{
    public $id;

    public $username;

    public $email;

    public $password;

    public $role;

    public function initialize()
    {
        $this->hasMany(
            'id',
            'UsersGroups',
            'users_id'
        );

        $this->hasManyToMany(
            'id',
            'UsersGroups',
            'users_id',
            'groups_id',
            'Groups',
            'id',
            ['alias' => 'relatedGroups']
        );

        $this->hasOne(
            'role',
            'Roles',
            'id'
        );
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator([
                'message' => 'Invalid email given'
            ]));
        $validator->add(
            'email',
            new UniquenessValidator([
                'message' => 'Sorry, The email was registered by another user'
            ]));
        $validator->add(
            'username',
            new UniquenessValidator([
                'message' => 'Sorry, That username is already taken'
            ]));

        return $this->validate($validator);
    }

    public function findUsersWithGroupConcatSql($searchStr = null)
    {
        $query = $this->modelsManager->createBuilder()
            ->columns([
                'Users.id',
                'Users.username',
                'Users.email',
                'Roles.name as role',
                "GROUP_CONCAT(Groups.name) as gc",
            ])
            ->addfrom('Users')
            ->leftjoin('Roles','Roles.id = Users.role','Roles')
            ->leftjoin('UsersGroups','Users.id = UsersGroups.users_id','UsersGroups')
            ->leftjoin('Groups','UsersGroups.groups_id = Groups.id','Groups')
            ->orderBy('Users.id')
            ->groupBy('Users.id');

        if(!empty($searchStr)) {
            $query->where("Users.email LIKE :email:", ["email" => "%" . $searchStr . "%"])
                ->orWhere("Groups.name LIKE :groupName:", ["groupName" => "%" . $searchStr . "%"]);
        }
        return $query->getQuery()->execute();
    }
}