<?php

namespace app\controllers;
use app\models\Post;

//this is an example controller class, feel free to delete
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

        $post = new Post();
        $post->savePost(
            [
                'title' => $postData['title'],
                'content' => $postData['content'],
                'description' => $postData['description']
            ]
        );
        //add the getPost here 
        //return the id
        $postInfo = $this->getPost($postData['title']);
        http_response_code(200);
        echo json_encode($postInfo);
        
        //header('Location: ./assets/views/posts/postView.html');
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