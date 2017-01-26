<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 26-1-2017
 * Time: 02:28
 */
class perform_matching{

    public function update(){
        global $sql;
        global $config;
        $possible_matches = $sql->fetch_object("
        SELECT 
            articles.id as article_id, users.id as userid, article_keywords.keyword as keyword, user_keywords.weight as weight
        FROM 
            articles,users,article_keywords,user_keywords
        WHERE 
            articles.timestamp >= users.last_update AND
            article_keywords.article_id = articles.id AND
            user_keywords.user_id = users.id AND
            article_keywords.keyword = user_keywords.keyword
        ORDER BY users.id,article_id
        LIMIT 50");

        $prev_article = -1;
        $prev_user = -1;
        $sum = 0;
        foreach($possible_matches as $possible_match){
            if($prev_article != $possible_match->article_id){
                //new article, add previous article as match if needed
                $this->doPossibleMatch($prev_user,$prev_article,$sum);
                //now reset sum for new possible match
                $sum = 0;
            }

            $sum += $possible_match->weight;
            $prev_article = $possible_match->article_id;
            $prev_user = $possible_match->userid;

        }
    }

    /**
     * add match if weight is enought
     */
    private function doPossibleMatch($userId,$articleId,$totalweight){
        global $sql;
        global $config;
        if($totalweight > $config->minweight){
            //weight sufficient? add to matches table
            $sql->query();//todo
        }

    }

}