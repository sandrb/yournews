<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 24-1-2017
 * Time: 01:18
 */
class users {

    function allUsers(){
        global $sql;
        global $config;
        $allUsers = $sql->fetch_object("SELECT * FROM " . $config->dbprefix . "users");
        return $allUsers;
    }

    function login($userId){
        global $sql;
        global $config;
        $count = $sql->single_select("SELECT COUNT(*) as count FROM " . $config->dbprefix . "users WHERE id = '" . $sql->mysqli->real_escape_string($userId) . "'");

        if($count > 0){
            //user exists, login
            $_SESSION['userid'] = $userId;
            return true;
        }else{
            //no user exists, don't login and return false
            return false;
        }
    }
}