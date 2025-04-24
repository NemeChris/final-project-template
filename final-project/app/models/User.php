<?php

namespace app\models;

class User extends Model {
    public function getAllUsers() {
        return $this->findAll();
    }

    public function updateUserSessionExp($inputData){
        $query = "update users set sessionExpiration = :sessionExpiration where id = :id";
        return $this->query($query, $inputData);
    }

    public function getUserByID($id) {
        $query = "SELECT id, username, sessionExpiration  
                  FROM users 
                 WHERE id = :id;";                         
        $user = $this->query($query, ['id' => $id]);
        if (!$user) {                                          
            return false;  
        }
        return $user[0];
    }

    public function login($inputData) {
        $query = "SELECT id, username, password 
                  FROM users 
                 WHERE username = :username;";                      
        $member = $this->query($query, ['username' => $inputData['username']]); 
        if (!$member) {                                        
            return false;
        }
        $authenticated = password_verify($inputData['password'], $member[0]['password']); // Passwords match?
        return ($authenticated ? $member[0] : false);
    }


}