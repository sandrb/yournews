<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 26-1-2017
 * Time: 02:28
 */
class perform_matching{

    public function update($userid = null){
        global $sql;
        global $config;
        $usercheck = "";
        if($userid != null){
            $usercheck = "AND " . $config->dbprefix . "users.id = '" . $sql->mysqli->real_escape_string($userid) . "'";
        }
        $possible_matches = $sql->fetch_object("
        SELECT 
            " . $config->dbprefix . "articles.id as article_id, " . $config->dbprefix . "users.id as userid, " . $config->dbprefix . "article_keywords.keyword as keyword, " . $config->dbprefix . "user_keywords.weight as weight
        FROM 
            " . $config->dbprefix . "articles," . $config->dbprefix . "users," . $config->dbprefix . "article_keywords," . $config->dbprefix . "user_keywords
        WHERE 
            " . $config->dbprefix . "articles.timestamp >= " . $config->dbprefix . "users.last_update AND
            " . $config->dbprefix . "article_keywords.article_id = " . $config->dbprefix . "articles.id AND
            " . $config->dbprefix . "user_keywords.user_id = " . $config->dbprefix . "users.id AND
            " . $config->dbprefix . "article_keywords.keyword = " . $config->dbprefix . "user_keywords.keyword " . $usercheck . "
        ORDER BY userid,article_id");
        $this->updateUsers($userid);

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
            $sql->query("INSERT INTO `" . $config->dbprefix . "matches` (`user`, `article`) VALUES ('" . $userId . "', '" . $articleId . "');");
            return true;
        }else{
            return false;
        }
    }

    /**
     * updates the last_update of all users
     */
    private function updateUsers($userid = null){
        global $sql;
        global $config;
        if($userid != null){

            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = NOW() WHERE id = " . $userid . " LIMIT 1");
        }else{
            $sql->query("UPDATE " . $config->dbprefix . "users SET last_update = NOW()");
        }
    }

}