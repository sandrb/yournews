<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 9-1-2017
 * Time: 13:48
 */

class admin {
    function overview(){
        global $sql;
        global $config;
        $articles = $sql->fetch_object("SELECT " . $config->dbprefix . "input_sites.id,domain,COUNT(" . $config->dbprefix . "articles.id) AS articles from " . $config->dbprefix . "input_sites LEFT JOIN " . $config->dbprefix . "articles on(" . $config->dbprefix . "input_sites.id = " . $config->dbprefix . "articles.input_site) GROUP BY " . $config->dbprefix . "input_sites.id");
        print_r($articles);
    }
}