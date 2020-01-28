<?php

namespace App\Controller;

use App\App;
use App\View\Navigation;
use Core\Page\Controller;

class Base extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->page['stylesheets'] = [
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css',
            'css/style.css'
        ];

        $this->page['scripts']['body_end'] = [
            'https://code.jquery.com/jquery-3.3.1.slim.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
        ];

        $nav_view = new Navigation([
            [
                'link' => 'register',
                'title' => 'Register'
            ],
            [
                'link' => 'fetch-posts',
                'title' => 'Fetch Posts'
            ]
        ]);

        $this->page['header'] = $nav_view->render();
    }

}
