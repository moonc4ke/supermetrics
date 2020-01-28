<?php

use Core\Page\Router;

Router::addRoute('/home', '\App\Controller\Home');
Router::addRoute('/register', '\App\Controller\Register');
Router::addRoute('/fetch-posts', '\App\Controller\FetchPosts');
