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
        global $config;
        $sites = $sql->fetch_object("SELECT * FROM " . $config->dbprefix . "input_sites");
        $return = array();
        foreach($sites as $site){
            $return[] = $this->singleUpdate($site->id, $site);
        }

        $return[] = $this->clearOld();

        return $return;
    }

    public function singleUpdate($id,$site = null){
        global $sql;
        global $config;
        if($site == null){
            //if needed: fill in missing values
            $site = $sql->fetch_object_single_row("SELECT * FROM " . $config->dbprefix . "input_sites WHERE id = " . $id . " LIMIT 1");
        }
        $forbidden = $sql->fetch_object("SELECT text FROM " . $config->dbprefix . "forbidden_links WHERE input_site = " . $id);
        $visited = $sql->fetch_object("SELECT url FROM " . $config->dbprefix . "articles WHERE input_site = " . $id);

        $return = array();
        $return["id"] = $id;
        $return["domain"] = $site->domain;
        $return["amount"] = 0;
        //remove (most of the) layout, each website has it's specific area_query
        $dom = new DomDocument();
        $dom->loadHTMLFile("http://" . $site->domain);
        $finder = new DomXPath($dom);
        $nodes = $finder->query($site->area_query);
        $html = $dom->saveHTML($nodes->item(0));//the html within the tag that satisfies the query

        $dom->loadHTML($html);//continue with just this part

        foreach($dom->getElementsByTagName("a") as $link){
            $title = $link->nodeValue;
            $url = $link->getAttribute("href");

            //we want relative urls, so strip this from any content we don't want
            $url = str_replace(array("http://","https://","www.",$site->domain), "" , $url);

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
            $subdomain = null;
            if(strpos($url,".") !== false){
                list($subdomain,$url) = explode(".", $url);
                $subdomain .= ".";
            }

            //todo: improve this for: usatoday.com, foxsports.com, nbcsports.com
            $articledom = new DomDocument();
            $articledom->loadHTMLFile("http://" . $subdomain . $site->domain . $url);
            $articlefinder = new DomXPath($articledom);
            $articlenodes = $articlefinder->query($site->article_area_query);

            if($articlenodes->length >= 0){
                //only store stuff if the link actually contains an article
                $articlehtml = $articledom->saveHTML($articlenodes->item(0));//this check doesn't work 100%, so we have another filter in the next line
                if(strlen($articlehtml) > 2 && substr("$articlehtml",0,9) != "<!DOCTYPE"){

                    $sql->query("INSERT INTO `" . $config->dbprefix . "articles` ( `input_site`, `url`,  `raw_content`) VALUES ( '" . $site->id . "', '" . $sql->mysqli->real_escape_string($url) . "', '" . $sql->mysqli->real_escape_string($articlehtml) . "');");
                    $return["amount"]++;
                }
            }
        }
        return $return;
    }

    public function clearOld(){
        global $sql;
        global $config;
        //lastly: remove artiles more than one week old
        $sql->query("DELETE FROM " . $config->dbprefix . "articles WHERE timestamp < '" . date("Y-m-d H:i:s", time() - 7 * 24 * 60 * 60) . "'");
        //note: this automatically cascades onto the keywords tables due to the index dependencies.
        $return = array();
        $return["deleted_articles"] = $sql->mysqli->affected_rows;
        return $return;
    }
}