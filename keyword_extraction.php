<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 18-1-2017
 * Time: 23:31
 */
class keyword_extraction {

    public function update(){
        //one update for everything, no splitting up for as long as this completes in reasonable amount of time.
        global $sql;
        global $config;
        $return = array();

        $articles = $sql->fetch_object("
            SELECT * FROM " . $config->dbprefix . "articles 
            WHERE 
            content_text != ''  AND 
            (SELECT COUNT(*) FROM " . $config->dbprefix . "article_keywords WHERE " . $config->dbprefix . "article_keywords.article_id = " . $config->dbprefix . "articles.id) = 0");

        $return["articles"] = count($articles);
        $stopwords = $sql->fetch_object("SELECT * FROM " . $config->dbprefix . "stop_words ");
        $stopwordsarray = array();
        foreach($stopwords as $word){
            $stopwordsarray[] = $word->varchar;
        }

        $return["keywords"] = 0;

        foreach($articles as $article){
            //$keywords =
        }
        return $return;
    }
}