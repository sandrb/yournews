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

    function addUser($username,$keywords){
        global $sql;
        global $config;
        $count = $sql->single_select("SELECT COUNT(*) FROM " . $config->dbprefix . "users WHERE username = '" . $sql->mysqli->real_escape_string($username) . "'");
        if($count == 0){
            //enforce unique username
            $sql->query("INSERT INTO `" . $config->dbprefix . "users` ( `username`) VALUES ('" . $sql->mysqli->real_escape_string($username) . "');");
            $userId =  $sql->single_select("SELECT id FROM " . $config->dbprefix . "users WHERE username = '" . $sql->mysqli->real_escape_string($username) . "' LIMIT 1");
            //insert all keywords with equal weights
            $weight = floor($config->totalweight / count($keywords));
            foreach($keywords as $keyword){
                $sql->query("INSERT INTO `" . $config->dbprefix . "user_keywords` ( `user_id`, `keyword`, `weight`) VALUES ( '" . $userId . "', '" . $sql->mysqli->real_escape_string($keyword) . "', '" . $weight . "');");
            }
            file_get_contents($config->root . "perform_matching/" . $userId);
        }
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

    function logout(){
        global $config;
        session_destroy();
        header("location: " . $config->root);
    }

    function curUser(){
        global $sql;
        global $config;
        if(isset($_SESSION['userid'])){
            return $sql->fetch_object_single_row("SELECT * FROM " . $config->dbprefix . "users WHERE id = '" . $sql->mysqli->real_escape_string($_SESSION['userid']) . "' LIMIT 1");
        }else{
            return null;
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

    /**
     * adds keyword to this user's list with average weight
     */

    function addKeyword($keyword){
        global $sql;
        global $config;

        $curUser = $this->curUser();
        $userId = $curUser->id;
        $inDB = $sql->single_select("SELECT count(*) FROM `" . $config->dbprefix . "user_keywords` WHERE user_id ='" . $sql->mysqli->real_escape_string($userId) . "' AND keyword = '" . $sql->mysqli->real_escape_string($keyword) . "'");
        //don't add dupliactes
        if($inDB == 0){
            $totalweight = $sql->single_select("SELECT SUM(weight) FROM `" . $config->dbprefix . "user_keywords` WHERE user_id ='" . $sql->mysqli->real_escape_string($userId) . "'");
            $amountKeywords = $sql->single_select("SELECT count(*) FROM `" . $config->dbprefix . "user_keywords` WHERE user_id ='" . $sql->mysqli->real_escape_string($userId) . "'");
            $averageWeight = ceil( $totalweight / $amountKeywords );//ceil the average to ensure they remain integers
            $sql->query("INSERT INTO `" . $config->dbprefix . "user_keywords` (`user_id`, `keyword`, `weight`) VALUES ( '" . $sql->mysqli->real_escape_string($userId) . "', '" . $sql->mysqli->real_escape_string($keyword) . "', '" . $averageWeight . "');");
            //fix max weight constraint
            $this->fix_max_weigth($userId);
        }
    }

    /**
     * Increases the weight of the keywords matched between @articleId and @userId
     */
    function improve_match($userId,$articleId){
        global $sql;
        global $config;

        $keywords = $sql->fetch_object("SELECT keyword 
              FROM `" . $config->dbprefix . "article_keywords` 
              WHERE `article_id` = '" . $sql->mysqli->real_escape_string($articleId) . "' AND 
              keyword IN(SELECT keyword FROM `" . $config->dbprefix . "user_keywords` WHERE user_id = '" . $sql->mysqli->real_escape_string($userId) . "')");

        //calculate increase per matched keyword
        $increase = floor($config->weightChange / count($keywords));
        foreach($keywords as $keyword){
            $sql->query("UPDATE " . $config->dbprefix . "user_keywords SET weight = weight + " . $increase . " WHERE user_id = '" . $sql->mysqli->real_escape_string($userId) . "' AND keyword = '" . $sql->mysqli->real_escape_string($keyword->keyword) . "'");
        }

        $this->fix_max_weigth($userId);
    }

    /**
     * Decreases overall keyword weights of user $userId where needed to ensure the total weight is no more than $config->totalweight
     */
    function fix_max_weigth($userId){
        global $sql;
        global $config;

        $totalweight = $sql->single_select("SELECT SUM(weight) FROM `" . $config->dbprefix . "user_keywords` WHERE user_id ='" . $sql->mysqli->real_escape_string($userId) . "'");
        if($totalweight > $config->totalweight){
            $rerun = false;
            $keywords = $sql->fetch_object("SELECT id,keyword,weight FROM `" . $config->dbprefix . "user_keywords` WHERE user_id ='" . $sql->mysqli->real_escape_string($userId) . "'");
            $decrease = ceil( ($totalweight - $config->totalweight) / count($keywords));

            foreach($keywords as $keyword){
                if($keyword->weight <= $decrease){
                    //keyword weight would be below 0, remove keyword and rerun afterwards
                    $sql->query("DELETE FROM `" . $config->dbprefix . "user_keywords` WHERE id = " . $keyword->id . " LIMIT 1");
                    $rerun = true;
                }else{
                    //decreaes weight
                    $sql->query("UPDATE " . $config->dbprefix . "user_keywords SET weight = weight - " . $decrease . " WHERE id = '" . $keyword->id . "'");
                }
            }

            if($rerun){
                $this->fix_max_weigth($userId);
            }else{
                //reset lastupdate and redo matching
                $this->resetUpdate($userId);
                file_get_contents($config->root . "perform_matching/" . $userId);
            }
        }
    }


    //resets all user-related data to standard
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
(27, 3, 'minaj', 100),
(28, 3, 'hilton', 100),
(29, 3, 'bieber', 100),
(30, 3, 'pitt', 100),
(31, 3, 'gomez', 100),
(32, 3, 'oscar', 100),
(33, 3, 'weeknd', 100),
(34, 3, 'mariah', 100),
(35, 3, 'kardashian', 100),
(36, 3, 'Kanye', 100);");
        $return["keywords_added"] = $sql->mysqli->affected_rows;
        $return["matching_result"] = file_get_contents($config->root . "perform_matching");

        return $return;
    }
}