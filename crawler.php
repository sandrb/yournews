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
        $sites = $sql->fetch_object("SELECT * FROM input_sites");
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
        $forbidden = $sql->fetch_object("SELECT text FROM forbidden_links WHERE input_site = " . $id);
        $visited = $sql->fetch_object("SELECT url FROM articles WHERE input_site = " . $id);


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

            if(strlen(trim($url)) <= 2 || strlen(trim($title)) <= 2){
                continue;//skip short urls or titles
            }

            if(is_numeric($title)){
                continue;//skip numeric-only titles (some kind of ID, but not a title we want)
            }

            if(strpos($url, 'javascript:') !== false){
                continue;//nove javascript links
            }

            $continue = false;
            foreach($forbidden as  $search){
                //skip all manually added forbidden links (speeds up the process significantly)
                if(strpos($url,$search->text) !== false || strpos($title,$search->text) && !$continue){
                    $continue = true;
                }
            }
            if($continue){
                continue;
            }


            //we want relative urls, so strip this from any content we don't want
            $url = str_replace(array("http://","https://","www.",$site->domain), "" , $url);

            //skip urls that are already in the database
            foreach($visited as $visurl){
                if($url == $visurl->url){
                    $continue = true;
                }
            }
            if($continue){
                continue;
            }

            //add this url to visited pages
            $visited[]->url = $url;

            //go to article page and retrieve contents
            $article = file_get_contents("http://" . $site->domain . $url);

            echo $title . ":" . $url . "<br>\n";
        }
        echo "<p><hr><p>";
    }
}