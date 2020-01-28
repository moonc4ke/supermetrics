<?php

namespace App\Controller;

use App\App;
use Core\Page\View;

class Register extends Base
{

    /** @var \App\Objects\Form\Register */
    protected $form;
    protected $input;

    public function __construct()
    {
        parent::__construct();

        $this->form = new \App\Objects\Form\Register();
        $status = $this->form->process();
        $this->input = $this->form->getInput();

        $this->form = new \App\Objects\Form\Register();

        $this->page['title'] = 'Register';

        $this->page['content'] = $this->form->render();

        if(isset($_COOKIE['tokenUser'])) {
            $view = new View([
                'tokenUser' => $_COOKIE['tokenUser'],
                'tokenEmail' => $_COOKIE['tokenEmail'],
                'tokenClientId' => $_COOKIE['tokenClientId'],
                'slToken' => $_COOKIE['slToken'],
            ]);
            
            $this->page['content-extension'] = $view->render(ROOT_DIR . '/app/views/registerInfo.tpl.php');
        }
    }
}