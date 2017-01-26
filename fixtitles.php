<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 25-1-2017
 * Time: 15:58
 */


class fixtitles {
    function update(){
        global $sql;
        global $config;
        $todos = $sql->fetch_object("SELECT " . $config->dbprefix . "articles.id as articleid, input_site, domain, url 
        FROM  " . $config->dbprefix . "articles, " . $config->dbprefix . "input_sites 
        WHERE title = '' AND  " . $config->dbprefix . "articles.input_site = " . $config->dbprefix . "input_sites.id 
        ORDER BY RAND() LIMIT 50");

        $return = array();
        foreach($todos as $article){
            $url = "http://" . $article->domain .  $article->url;
            $dom = new DomDocument('1.0', 'utf-8');
            $dom->loadHTMLFile($url);
            $finder = new DomXPath($dom);

            if(in_array($article->input_site,array(1,3,4,6,7,8,10,11,12,13,14,15,16,17,18,19))){
                $hTwo= $dom->getElementsByTagName('h1'); // here u use your desired tag
                $title = $hTwo->item(0)->nodeValue;
            }else if($article->input_site == 2){
                $nodes = $finder->query("//*[contains(@class, 'asset-headline')]");
                $title = $dom->saveHTML($nodes->item(0));
            }else if($article->input_site == 9){
                $hTwo= $dom->getElementsByTagName('h1');
                $title = $hTwo->item(1)->nodeValue;//get  h1 tag 1 insetad of 0
            }
            $title = preg_replace("/\s+/", " ",$title);

            $title = strip_tags($title);

            if(empty($title)){
                $title = "ERROR";
            }

            $sql->query("UPDATE  " . $config->dbprefix . "articles SET title = '" . $sql->mysqli->real_escape_string($title) . "' WHERE id = " . $article->articleid . " LIMIT 1");
            $return[] = array($url,$title);
        }
        return $return;
    }

    function export(){
        global $sql;
        $exports = $sql->fetch_object("SELECT id , title FROM articles WHERE title != '' AND title != 'ERRROR'");
        echo "UPDATE articles SET ";
        foreach($exports as $export){
            echo "title = '" . $sql->mysqli->real_escape_string(strip_tags($export->title)) . "' WHERE id = '" . $export->id . "' AND ";
        }
    }

}