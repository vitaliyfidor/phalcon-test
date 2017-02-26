<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\Email as validEmail;
use Phalcon\Validation\Validator\PresenceOf;

class UsersForm extends Form
{
    /**
     * Инициализация формы
     */
    public function initialize($entity = null, $options = [])
    {
        if (isset($options["edit"])) {
            $this->add(
                new Hidden("id")
            );
        } else {
            // Confirm Password
            $repeatPassword = new Password('repeatPassword');
            $repeatPassword->setLabel('Repeat Password');
            $repeatPassword->addValidators([
                new PresenceOf([
                    'message' => 'Confirmation password is required'
                ])
            ]);
            $this->add($repeatPassword);
        }

        //Username
        $name = new Text("username");
        $name->setLabel("Username");
        $name->setFilters(['alpha']);
        $name->setFilters(
            [
                "striptags",
                "string",
            ]
        );
        $name->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Username required",
                    ]
                )
            ]
        );
        $this->add($name);

        //Email
        $email = new Email("email");
        $email->setLabel("Email");
        $email->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Email required",
                    ]
                ),
                new validEmail([
                    "message" => "Email not valid",
                ])
            ]
        );
        $this->add($email);

        // Password
        $password = new Password("password");
        $password->setLabel("Password");
        $password->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Password required",
                    ]
                )
            ]
        );
        $this->add($password);

        //Roles
        $roles = new Select(
            "role",
            Roles::find(),
            [
                "using"      => [
                    "id",
                    "name",
                ],
                'class'      => 'form-control',
                "useEmpty"   => false,
            ]
        );
        $roles->setLabel('Role');
        $this->add($roles);

        //Groups
        $groups = new Select(
            "groupsIds[]",
            Groups::find(),
            [
                "using"      => [
                    "id",
                    "name",
                ],
                'class'      => 'form-control',
                'multiple'   => true
            ]
        );
        $groups->setLabel('Groups');
        $this->add($groups);

    }
}