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
        $return["no_keywords"] = 0;

        foreach($articles as $article){
            $keywords = $this->filterKeywords($article->content_text, $stopwordsarray);
            $return["keywords"] += count($keywords);
            $values = "";
            //merge all keywords into one insert query to keep loading times under control
            foreach($keywords as $keyword){
                $values .= "('" . $article->id . "','" . $sql->mysqli->real_escape_string($keyword) . "'),";
                //
            }
            $values = substr($values,0,strlen($values) - 1);//remove last ,
            if(empty($values)){
                //no keywords found? Do make an insert to prevent this article from being extracted again and again and to make it tracable for debugging
                $values = "('" . $article->id . "','no keywords found')";
                $return["no_keywords"]++;
            }
            $sql->query("INSERT INTO `article_keywords` (`article_id`, `keyword`) VALUES " . $values);
        }
        return $return;
    }

    /**
     * @param $text The input text
     * @param $stopwords The keywords to be removed from this list
     * @return an array with keywords of the inputted text
     */
    private function filterKeywords($text,$stopwords){
        // Replace all non-word chars with comma
        $pattern = '/[0-9\W]/';
        $text = preg_replace($pattern, ',', $text);

        // Create an array from $text
        $text_array = explode(",",$text);

        // remove whitespace and lowercase words in $text
        $text_array = array_map(function($x){return trim(strtolower($x));}, $text_array);

        $keywords = array();

        foreach ($text_array as $term) {
            //don't store stopwords, duplicates or short words
            if (!in_array($term, $stopwords) && !in_array($term,$keywords) && strlen($term) >= 3) {
                $keywords[] = $term;
            }
        };

        return $keywords;
    }
}