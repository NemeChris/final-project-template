<?php

namespace app\models;

class Post extends Model {

    protected $table = "blog-posts";

    public function getAllPosts() {
        return $this->findAll();
    }

    public function savePost($inputData){
        $query = "insert into `blog-posts` (title, content, description, date) values (:title, :content, :description, :date);";
        return $this->query($query, $inputData);
    }

    public function updatePost($inputData){
        $query = "update `blog-posts` set title = :title, content = :content, description = :description, date = :date where id = :id;";
        return $this->query($query, $inputData);
    }

    public function deletePost($inputData){
        $query = "delete from `blog-posts` where id = :id";
        return $this->query($query, $inputData);
    }

    public function getPostByTitle($title) {
        if ($title) {
            $query = "select * from `blog-posts` WHERE title like :title";
            return $this->fetchAllWithParams($query, ['title' => '%' . $title . '%']);
        }
    }

    public function getRecentPosts() {
        $query = "select * from `blog-posts` order by id desc limit 4;";
        return $this->fetchAllWithParams($query);
    }

    public function getPostById($id){
        $query = "select * from `blog-posts` WHERE id = :id";
        return $this->fetchAllWithParams($query, ['id' => $id])[0] ?? null;
    }

}