<?php

require_once('modules/db/Mysql.php');
require_once('modules/game/Points.php');

class App {
    private $db;

    function __construct() {
        $this->db = new Mysql();
    }

    function getSides($params) {
        $points = new Points($this->db->getConnection() ,$params['ax'], $params['ay'], $params['az'],
                            $params['bx'], $params['by'], $params['bz'],
                            $params['cx'], $params['cy'], $params['cz']);
        return $points->getSides();
    }

    function getAngles($params) {
        $points = new Points($this->db->getConnection() ,$params['ax'], $params['ay'], $params['az'],
            $params['bx'], $params['by'], $params['bz'],
            $params['cx'], $params['cy'], $params['cz']);
        return $points->getAngles();
    }

    function getSquare($params) {
        $points = new Points($this->db->getConnection() ,$params['ax'], $params['ay'], $params['az'],
            $params['bx'], $params['by'], $params['bz'],
            $params['cx'], $params['cy'], $params['cz']);
        return $points->getSquare();
    }

    function getUsers() {
        return $this->db->getUsers();
    }

    function postUser($params) {
        return $this->db->postUser($params['login'], $params['password']);
    }
}
