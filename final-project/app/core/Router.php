<?php

namespace app\core;

use app\controllers\MainController;
use app\controllers\PostController;
use app\controllers\AuthController;

class Router {
    public $uriArray;

    function __construct() {
        AuthHelper::checkSession();
        $this->uriArray = $this->routeSplit();
        error_log("URI Array: " . print_r($this->uriArray, true));
        $this->handleMainRoutes();
        $this->handlePostRoutes();
        $this->handleAuthRoutes();
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

        if ($this->uriArray[1] === 'clickPost' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $mainController = new MainController();
            $mainController->getPostByID($_GET['id']);
        }

        if ($this->uriArray[1] === 'api' && $this->uriArray[2] === 'posts' && $this->uriArray[3] === 'recent' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $mainController = new MainController();
            $mainController->getRecentPosts();
        }

        if ($this->uriArray[1] === 'blog') {
            $mainController = new MainController();
            $mainController->returnView('./assets/views/main/createBlog.html');
        }

        if($this->uriArray[1] === 'see-all'){
            $mainController = new MainController();
            $mainController->returnView('./assets/views/main/see-all.html');
        }

        if ($this->uriArray[1] === 'about') {
            $mainController = new MainController();
            $mainController->returnView('./assets/views/main/about-page.html');
        }

        if($this->uriArray[1] === 'license'){
            $mainController = new MainController();
            $mainController->getLicense();
        }

    }

    protected function handlePostRoutes() {
        if ($this->uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $this->uriArray[2];
            $postController = new PostController();
            if($id){
                $postController->getPostByID($id);
            }else{
                $postController->getPosts();
            }
        }

        if ($this->uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $postController = new PostController();
            $postController->savePost();
        }

        if ($this->uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $id = isset($this->uriArray[2]) ? intval($this->uriArray[2]) : null;
            $postController = new PostController();
            $postController->updatePost($id);
        }

        if ($this->uriArray[1] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $id = isset($this->uriArray[2]) ? intval($this->uriArray[2]) : null;
            $postController = new PostController();
            $postController->deletePost($id);
        }

        if ($this->uriArray[1] === 'viewPost') {
            $postController = new PostController();
            $postController->viewPost();
        }

        if ($this->uriArray[1] === 'admin') {
            AuthHelper::authRoute();
            $postController = new PostController();
            $postController->returnView('./assets/views/posts/authorView.html');
        }

        if($this->uriArray[1] === 'update-post'){
            AuthHelper::authRoute();
            $postController = new PostController();
            $postController->returnView('./assets/views/posts/post-update.html');
        }

        if($this->uriArray[1] === 'search-view'){
            $postController = new PostController();
            $postController->returnView('./assets/views/posts/search-view.html');
        }

        if($this->uriArray[1] === 'api' && $this->uriArray[2] === 'posts' && $_SERVER['REQUEST_METHOD'] === 'GET'){
            $postTitle = $this->uriArray[3];
            $postController = new PostController();
            $postController->getPostByTitle($postTitle);
        }
    }

    protected function handleAuthRoutes(){
        if($this->uriArray[1] === 'login' && $_SERVER['REQUEST_METHOD'] === 'GET'){
            $authController = new AuthController();
            $authController->loginView();
        }

        if($this->uriArray[1] === 'api' && $this->uriArray[2] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $authController = new AuthController();
            $authController->login();
        }

        if($this->uriArray[1] === 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST'){
            $authController = new AuthController();
            $authController->logout();
        }
    }
}