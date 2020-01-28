<?php

namespace App;

use Core\Page\Router;

class App
{

    public function run()
    {
        $controller = Router::getRouteController($_SERVER['REQUEST_URI']);

        if ($controller) {
            print $controller->onRender();
        } else {
            header('Location: /home');
            exit();
        }
    }
}
