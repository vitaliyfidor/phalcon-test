<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;

class SearchForm extends Form
{
    /**
     * Form Initialisation
     */
    public function initialize()
    {
        $search = new Text("search");
        $search->setLabel("Search users by Email or Group name");
        $search->setFilters(
            [
                "striptags",
                "string",
            ]
        );
        $this->add($search);
    }
}