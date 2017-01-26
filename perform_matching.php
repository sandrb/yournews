<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 26-1-2017
 * Time: 02:28
 */
class perform_matching{

    public function upddate(){
        global $sql;
        global $config;
        $possible_matches = $sql->fetch_object("
        SELECT 
            articles.id as article_id, users.id as userid
        FROM 
            articles,users
        WHERE 
            articles.timestamp >= users.last_update 
        ORDER BY users.id 
        LIMIT 50");

        $user_keywords = array();

        foreach($possible_matches as $possible_match){
            if(!isset($user_keywords[$possible_match->userid])){
                $user_keywords[$possible_match->userid] = $sql->fetch_object("SELECT keyword,weight FROM user_keywords WHERE user_id = " . $possible_match->userid);
                echo "get user keywords <br>";
            }
        }
    }

}