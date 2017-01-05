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
        //remove (most of the) layout, each website has it's specific area_query
        $dom = new DomDocument();
        $dom->loadHTMLFile("http://" . $site->domain);
        $finder = new DomXPath($dom);
        $nodes = $finder->query($site->area_query);
        $html = $dom->saveHTML($nodes->item(0));//the html within the tag that satisfies the query

        echo $id . ": " . $site->domain . " (" . $nodes->length . ")<br>";
        //todo
    }
}