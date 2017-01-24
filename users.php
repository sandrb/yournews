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

}