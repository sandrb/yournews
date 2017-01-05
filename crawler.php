<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 02:00
 */
class crawler {

    function update(){
        global $sql;
        $sites = $sql->fetch_object("SELECT id,domain FROM input_sites");
        foreach($sites as $site){
            $this->singleUpdate($site->id, $site->domain);
        }
    }

    function singleUpdate($id,$domain = null){
        global $sql;
        if($domain == null){
            $domain = $sql->single_select("SELECT domain FROM input_sites WHERE id = " . $id);
            /*$site = $sql->fetch_object("SELECT domain FROM input_sites WHERE id = " . $id);
            $domain = $site->domain;*/
        }
        echo $id . ": " . $domain . "<br>";
        //todo
    }
}