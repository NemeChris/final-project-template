<?php

namespace app\models;

abstract class Model {

    public function findAll() {
        $query = "select * from `blog-posts`";
        return $this->query($query);
    }

    private function connect() {
        $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
        $con = new \PDO($string, DBUSER, DBPASS);
        return $con;
    }

    public function query($query, $data = []) {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        return false;
    }

    public function fetchAllWithParams($query, $data = []) {
        $connection = $this->connect();
        $statementObject = $connection->prepare($query);
        $successOrFail = $statementObject->execute($data);
        if ($successOrFail) {
            $result = $statementObject->fetchAll();
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        return false;
    }
}