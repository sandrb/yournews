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
            articles.id as article_id, users.id as userid, article_keywords.keyword as keyword
        FROM 
            articles,users,article_keywords
        WHERE 
            articles.timestamp >= users.last_update AND
            article_keywords.article_id = articles.id AND
            keyword IN (SELECT keyword FROM user_keywords WHERE user_keywords.user_id = users.id)
        ORDER BY users.id
        LIMIT 50");

        $user_keywords = array();

        foreach($possible_matches as $possible_match){
            if(!isset($user_keywords[$possible_match->userid])){
                $user_keywords[$possible_match->userid] = $sql->fetch_object("SELECT keyword,weight FROM user_keywords WHERE user_id = " . $possible_match->userid);
                $user_keywords[$possible_match->userid]["merged"] = null;
                foreach($user_keywords[$possible_match->userid] as $combo){
                    if(!empty($combo->keyword)){
                        $user_keywords[$possible_match->userid]["merged"] .= '"' . $combo->keyword . '",';
                    }
                }
                $user_keywords[$possible_match->userid]["merged"] = substr($user_keywords[$possible_match->userid]["merged"],0, strlen($user_keywords[$possible_match->userid]["merged"]) - 1);
                $user_keywords[$possible_match->userid]["merged"] = "(" . $user_keywords[$possible_match->userid]["merged"] . ")";
            }

            $article_keywords = $sql->fetch_object("SELECT keyword FROM article_keywords WHERE article_id = " . $possible_match->article_id . " AND keyword IN ". $user_keywords[$possible_match->userid]["merged"]);
            echo "article keywords:";
            print_r($article_keywords);
        }
    }

}