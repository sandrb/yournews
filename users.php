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

    function resetUpdate($userId = null){
        global $sql;
        global $config;
        if($userId == null){
            $sql->query("TRUNCATE TABLE `matches`");
            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = '2015-12-31 23:00:00'");
        }else{
            $sql->query("DELETE from `matches` WHERE user = '" . $sql->mysqli->real_escape_string($userId) . "'");
            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = '2015-12-31 23:00:00' WHERE users.id = '" . $sql->mysqli->real_escape_string($userId) . "' LIMIT 1");
        }
        $return = array();
        $return["affected_users"] = $sql->mysqli->affected_rows;
        return $return;
    }
}