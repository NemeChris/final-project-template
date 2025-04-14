<?php

namespace app\core;

use app\controllers\MainController;
use app\controllers\PostController;

class Router {
    public $uriArray;

    function __construct() {
        $this->uriArray = $this->routeSplit();
        $this->handleMainRoutes();
        $this->handlePostRoutes();
    }

    protected function routeSplit() {
        $removeQueryParams = strtok($_SERVER["REQUEST_URI"], '?');
        return explode("/", $removeQueryParams);
    }

    protected function handleMainRoutes() {
        if ($this->uriArray[1] === '' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $mainController = new MainController();
            $mainController->homepage();
        }

        //get post by title (used in homepage)
        //using api and post confused the conditional with one below and got all recent posts
        if ($this->uriArray[1] === 'clickPost' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $mainController = new MainController();
            //echo $_GET['postTitle'];
            $mainController->getPost($_GET['postTitle']);
        }


        if ($this->uriArray[1] === 'api' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $mainController = new MainController();
            $mainController->getRecentPosts();
        }


        // if ($this->uriArray[1] === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $mainController = new MainController();
        //     $mainController->homepage();
        // }
    }

    protected function handlePostRoutes() {
        //change createPost to post to fit restful API
        //change view post to ??
        //change display to post (differes from first due to request method GET)
        //change it in respective ajax too
        if ($this->uriArray[1] === 'createPost' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $postController = new PostController();
            $postController->savePost();
        }

        if ($this->uriArray[1] === 'viewPost') {
            $postController = new PostController();
            $postController->viewPost();
        }

        if ($this->uriArray[1] === 'display' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $this->uriArray[2];
            $postController = new PostController();
            $postController->getPostByID($id);
        }

        //give json/API requests a api prefix
        // if ($this->uriArray[1] === 'api' && $this->uriArray[2] === 'users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        //     $userController = new UserController();
        //     $userController->getUsers();
        // }
    }
}