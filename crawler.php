<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 02:00
 */
class crawler {

    public function update(){
        global $sql;
        $sites = $sql->fetch_object("SELECT * FROM input_sites WHERE domain = 'politico.com'");
        foreach($sites as $site){
            $this->singleUpdate($site->id, $site);
        }
    }

    public function singleUpdate($id,$site = null){
        global $sql;
        if($site == null){
            //if needed: fill in missing values
            $site = $sql->fetch_object_single_row("SELECT * FROM input_sites WHERE id = " . $id . " LIMIT 1");
        }
        //remove (most of the) layout, each website has it's specific area_query
        $dom = new DomDocument();
        $dom->loadHTMLFile("http://" . $site->domain);
        $finder = new DomXPath($dom);
        $nodes = $finder->query($site->area_query);
        $html = $dom->saveHTML($nodes->item(0));//the html within the tag that satisfies the query

        echo "<b>" . $id . ": " . $site->domain . " (" . $nodes->length . ")</b><br>\n";
        //echo $html;
        $dom->loadHTML($html);//continue with just this part

        foreach($dom->getElementsByTagName("a") as $link){
            $title = $link->nodeValue;
            $url = $link->getAttribute("href");
            echo $title . ": " . $url . "<br>\n";

            if(strlen($url) <= 2 || strlen($title) <= 2){
                continue;//skip short urls or titles
            }

            if(is_numeric($title)){
                continue;//skip numeric-only titles (some kind of ID, but not a title we want)
            }

            if(strpos($url, 'javascript:') !== false){
                continue;//nove javascript links
            }
        }
        echo "<p><hr><p>";
        //todo
    }
}