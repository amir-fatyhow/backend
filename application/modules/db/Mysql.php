<?php

class Mysql {
    private $connection;
    function __construct() {
        $server =  "localhost";
        $username = "root";
        $password = "";

        $this->connection = new mysqli($server, $username, $password);

        $sql = "CREATE DATABASE IF NOT EXISTS mathDB";

        if ($this->connection->query($sql) === true) {
            $this->connection = new mysqli($server, $username, $password, "mathDB");
        }

        $table = "CREATE TABLE IF NOT EXISTS points(
            id INT PRIMARY KEY AUTO_INCREMENT,
            side_a DOUBLE,
            side_b DOUBLE,
            side_c DOUBLE,
            square DOUBLE,
            angle1 DOUBLE,
            angle2 DOUBLE,
            angle3 DOUBLE
        )";

        $this->connection->query($table);

        $users = "CREATE TABLE IF NOT EXISTS users(
            id INT PRIMARY KEY AUTO_INCREMENT,
            login VARCHAR(255),
            password VARCHAR(255)
        )";

        $this->connection->query($users);
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getUsers() {
        $select = "SELECT * FROM users";
        $result = $this->connection->query($select);

        $answer = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $answer[] = $row;
            }
        }
        return $answer;
    }

    public function postUser($login, $pass) {
        $insert = "INSERT INTO users(login, password) VALUES('$login','$pass')";

        $this->connection->query($insert);
        return "added";
    }
}