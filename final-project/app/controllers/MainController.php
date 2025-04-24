<?php

namespace app\controllers;
use app\models\Post;

class MainController extends Controller {
    public function getRecentPosts($data = '') {
        $postModel = new Post();
        $query = !empty($data) ? $data : null;
        $post = $postModel->getRecentPosts($query);
        echo json_encode($post);
        exit();
    }

    public function getPost($data = '') {
        $postModel = new Post();
        $query = !empty($data) ? $data : null;
        $post = $postModel->getPost($query);
        echo json_encode($post);
        exit();
    }

    public function getPostByID($id) {
        $postModel = new Post();
        header("Content-Type: application/json");
        $post = $postModel->getPostById($id);
        echo json_encode($post);
        exit();
    }

    public function homepage() {
        //remember to route relative to index.php
        //require page and exit to return an HTML page
        
        $this->returnView('./assets/views/main/homepage.html');
    }

    public function notFound() {
    }

    public function getLicense(){
        header('Content-Type: application/json');
        echo json_encode(['licenseKey' => KEY]);
    }


}