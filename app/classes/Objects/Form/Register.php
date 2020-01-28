<?php

namespace App\Objects\Form;

use Core\Page\Objects\Form;

class Register extends Form
{

    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'email' => [
                    'label' => 'Email',
                    'type' => 'text',
                    'placeholder' => 'Your email',
                    'validate' => []
                ],
                'full_name' => [
                    'label' => 'Full name',
                    'type' => 'text',
                    'placeholder' => 'Your full name',
                    'validate' => []
                ],
            ],
            'pre_validate' => [],
            'validate' => [],
            'buttons' => [
                'submit' => [
                    'text' => 'Register'
                ]
            ]
        ]);
    }

}
