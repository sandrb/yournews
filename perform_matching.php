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
        ORDER BY users.id,article_id");

        $prev_article = -1;
        $prev_user = -1;
        $sum = 0;
        $return = array();
        foreach($possible_matches as $possible_match){
            //start update
            if($prev_article != $possible_match->article_id){
                //new article, add previous article as match if needed
                if($this->doPossibleMatch($prev_user,$prev_article,$sum)){
                    if(!isset($return[$prev_user])){
                        //one match for this user
                        $return[$prev_user] = 1;
                    }else{
                        //one more match for this user
                        $return[$prev_user]++;
                    }
                }

                //now reset sum for new possible match
                $sum = 0;
            }

            if($prev_user != $possible_match->userid){
                $this->updateUser($prev_user);
            }
            //end update

            $sum += $possible_match->weight;
            $prev_article = $possible_match->article_id;
            $prev_user = $possible_match->userid;

        }
        //perform the same udpate at the end
        if(!isset($return[$prev_user])){
            //one match for this user
            $return[$prev_user] = 1;
        }else{
            //one more match for this user
            $return[$prev_user]++;
        }

        $this->updateUser($prev_user);

        return $return;
    }

    /**
     * add match if weight is enough
     */
    private function doPossibleMatch($userId,$articleId,$totalweight){
        global $sql;
        global $config;
        if($totalweight > $config->minweight) {
            //weight sufficient? add to matches table
            $sql->query("INSERT INTO `matches` (`user`, `article`) VALUES ('" . $userId . "', '" . $articleId . "');");
            return true;
        }else{
            return false;
        }
    }

    /**
     * updates the last_update of a user
     */
    private function updateUser($userid){
        global $sql;
        global $config;
        $sql->query("UPDATE users SET last_update = NOW() WHERE id = " . $userid);
    }

}