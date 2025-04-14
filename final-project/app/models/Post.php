<?php

namespace app\models;

//this is an example model class, feel free to delete
class Post extends Model {

    protected $table = 'users';

    public function getAllUsers() {
        return $this->findAll();
    }

    public function savePost($inputData){
        $query = "insert into `blog-posts` (title, content, description) values (:title, :content, :description);";
        return $this->query($query, $inputData);
    }

    public function getPost($title) {
        if ($title) {
            $query = "select * from `blog-posts` WHERE title like :title";
            return $this->fetchAllWithParams($query, ['title' => '%' . $title . '%']);
        }
    }

    public function getRecentPosts() {
        $query = "select * from `blog-posts` order by id desc limit 3;";
        return $this->fetchAllWithParams($query);
    }

    public function getPostById($id){
        $query = "select * from `blog-posts` WHERE id = :id";
        return $this->fetchAllWithParams($query, ['id' => $id])[0] ?? null;
    }

}