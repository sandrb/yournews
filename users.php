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
            $sql->query("TRUNCATE TABLE `" . $config->dbprefix . "matches`");
            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = '2015-12-31 23:00:00'");
        }else{
            $sql->query("DELETE from `" . $config->dbprefix . "matches` WHERE user = '" . $sql->mysqli->real_escape_string($userId) . "'");
            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = '2015-12-31 23:00:00' WHERE id = '" . $sql->mysqli->real_escape_string($userId) . "' LIMIT 1");
        }
        $return = array();
        $return["affected_users"] = $sql->mysqli->affected_rows;
        return $return;
    }

    function fullReset(){
        global $sql;
        global $config;

        $return = array();
        $sql->query("DELETE FROM  `" . $config->dbprefix . "matches`");
        $return["matches_deleted"] = $sql->mysqli->affected_rows;

        $sql->query("DELETE FROM `" . $config->dbprefix . "user_keywords`");
        $return["keywords_deleted"] = $sql->mysqli->affected_rows;

        $sql->query("SET FOREIGN_KEY_CHECKS = 0;");
        $sql->query("DELETE FROM  `" . $config->dbprefix . "users`");
        $return["users_deleted"] = $sql->mysqli->affected_rows;
        $sql->query("SET FOREIGN_KEY_CHECKS = 1;");

        $sql->query("INSERT INTO `" . $config->dbprefix . "users` (`id`, `username`, `last_update`) VALUES
(1, 'user1', '2015-12-31 22:00:00'),
(2, 'user2', '2015-12-31 22:00:00'),
(3, 'user3', '2015-12-31 22:00:00');");
        $return["users_added"] = $sql->mysqli->affected_rows;

        $sql->query("INSERT INTO `" . $config->dbprefix . "user_keywords` (`id`, `user_id`, `keyword`, `weight`) VALUES
(1, 1, 'trump', 167),
(2, 1, 'elections', 167),
(3, 1, 'president', 166),
(4, 1, 'clinton', 167),
(5, 1, 'votes', 166),
(6, 1, 'obama', 166),
(7, 2, 'samsung', 100),
(8, 2, 'apple', 100),
(9, 2, 'microsoft', 100),
(10, 2, 'google', 100),
(11, 2, 'htc', 100),
(12, 2, 'philips', 100),
(13, 2, 'facebook', 100),
(14, 2, 'dell', 100),
(15, 2, 'sony', 100),
(16, 2, 'huawai', 100),
(27, 3, 'minaj', 101),
(28, 3, 'hilton', 100),
(29, 3, 'bieber', 100),
(30, 3, 'pitt', 100),
(31, 3, 'gomez', 100),
(32, 3, 'oscar', 100),
(33, 3, 'weeknd', 100),
(34, 3, 'mariah', 100),
(35, 3, 'kardashian', 100),
(36, 3, 'Kanye', 0);");
        $return["keywords_added"] = $sql->mysqli->affected_rows;

        return $return;
    }
}