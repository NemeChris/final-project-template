<?php

namespace app\controllers;
use app\models\Post;
use DateTime;
use DateInterval;

class PostController extends Controller {
    public function validatePost($inputData) {
        $errors = [];
        $title = $inputData['title'];
        $content = $inputData['content'];
        $description = $inputData['description'];

        if ($title) {
            $title = htmlspecialchars($title, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            if (strlen($title) < 20) {
                $errors['titleShort'] = 'title is too short';
            }
        } else {
            $errors['requiredTitle'] = 'title is required';
        }

        if ($content) {
            $content = htmlspecialchars($content, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            if (strlen($content) < 100) {
                $errors['contentShort'] = 'content is too short';
            }
        } else {
            $errors['requiredContent'] = 'content is required';
        }

        if ($description) {
            $content = htmlspecialchars($content, ENT_QUOTES|ENT_HTML5, 'UTF-8', true);
            if (strlen($content) < 20) {
                $errors['contentShort'] = 'description is too short';
            }
        } else {
            $errors['requiredDescription'] = 'description is required';
        }
        


        if (count($errors)) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }
        return [
            'title' => $title,
            'content' => $content,
            'description' => $description
        ];
    }

    public function savePost() {
        $inputData = [
            'title' => $_POST['title'] ?: null,
            'content' => $_POST['editorContent'] ?: null,
            'description' => $_POST['description'] ?: null
        ];

        $postData = $this->validatePost($inputData);

        $now = new DateTime();
        $formattedCurrentTime = $now->format('m-d-Y');
        $post = new Post();
        $post->savePost(
            [
                'title' => $postData['title'],
                'content' => $postData['content'],
                'description' => $postData['description'],
                'date' => $formattedCurrentTime
            ]
        );

        $postInfo = $this->getPostByTitle($postData['title']);
        http_response_code(200);
        echo json_encode($postInfo);
        
        exit();
    }

    public function updatePost($id){
        if(!$id){
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Post ID not provided']);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);
        if (empty($_PUT)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid or missing input']);
            exit();
        }
        header("Content-Type: application/json");
        $inputData = [
            'title' => $_PUT['title'] ?: null,
            'content' => $_PUT['editorContent'] ?: null,
            'description' => $_PUT['description'] ?: null
        ];
        $postData = $this->validatePost($inputData);
        $now = new DateTime();
        $formattedCurrentTime = $now->format('m-d-Y');
        $post = new Post();
        $post->updatePost(
            [
                'id' => $id,
                'title' => $postData['title'],
                'content' => $postData['content'],
                'description' => $postData['description'],
                'date' => $formattedCurrentTime
            ]
        );
        http_response_code(200);
        $postInfo = $this->getPostByID($id);
        if (!$postInfo) {
            http_response_code(500);
            header("Content-Type: application/json");
            echo json_encode(['error' => 'Post retrieval failed']);
            exit();
        }
        echo json_encode([
            'success' => true
        ]);
        exit();
    }

    public function deletePost($id){
        if(!$id){
            http_response_code(404);
            exit();
        }

        $post = new Post();
        $post->deletePost([
            'id' => $id
        ]);

        http_response_code(200);
        echo json_encode([
            'success' => true
        ]);

        exit();
    }

    public function getPosts() {
        $postModel = new Post();
        $posts = $postModel->getAllPosts();
        $this->returnJSON($posts);
    }

    public function getPostByTitle($data = '') {
        $postModel = new Post();
        $query = !empty($data) ? $data : null;
        $post = $postModel->getPostByTitle($query);
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

    public function getUsers() {
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $this->returnJSON($users);
    }

    public function viewPost(){
        $this->returnView('./assets/views/posts/postView.html');
    }

    public function usersView() {
        $this->returnView('./assets/views/users/usersView.html');
    }

}