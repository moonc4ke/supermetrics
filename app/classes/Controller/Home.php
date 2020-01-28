<?php

namespace App\Controller;

use App\App;
use Core\Page\View;

class Home extends Base
{

    protected $view;

    public function __construct()
    {
        parent::__construct();

        $view = new View();

        $this->page['title'] = 'Home';

        $this->page['content'] = $view->render(ROOT_DIR . '/app/views/home.tpl.php');
    }

}
