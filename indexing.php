<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 10-1-2017
 * Time: 20:19
 */
class indexing {

    public function update(){
        //one update for everything, no splitting up for as long as this completes in reasonable amount of time.
        global $sql;
        global $config;
        $articles = $sql->fetch_object("SELECT * FROM " . $config->dbprefix . "articles WHERE content_text = ''");
        $return = array();
        $return["articles"] = count($articles);
        $return["empty_articles"] = 0;
        foreach($articles as $article){
            //strip html tags and write to database
            $text_cleaned = $this->clean_text($article->raw_content);
            if($text_cleaned == -1){
                //also keep trackt of the articles that aren't of real use, for stats
                $return["empty_articles"]++;
            }
            $sql->query("UPDATE " . $config->dbprefix . "articles SET content_text = '" . $sql->mysqli->real_escape_string($text_cleaned) . "' WHERE id = " . $article->id . " LIMIT 1");
        }
        return $return;
    }

    private function clean_text($input){
        //cleans text, the returned text get's stored in the database
        $result = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $input);//for javascript
        $result = strip_tags($result);//for html stuff
        $result = trim($result);//for spaces
        if(strlen($result) < 25){
            //amount of chars too little to be usefull, we do store this to be able to see if the crawling went wrong.
            $result = -1;
        }
        return $result;
    }
}