<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{
    /**
     * Form Initialisation
     */
    public function initialize()
    {

        $name = new Text("username");

        $name->setLabel("Username");

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

    }
}