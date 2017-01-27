<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 00:19
 */

//load config
require_once("config.php");
$config = new config();

//error reporting
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//display_errors(true);

//sql connection
require_once("sql.php");
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

//for sessions
session_start();

//pageload

if($_GET['a'] == "files") {//something in files folder? simply include
    include("files/" . $_GET['b']);
}else if($_GET['a'] == "crawl") {
    //crawls for new articles
    require_once("crawler.php");
    $start = date("Y-m-d H:i:s");
    $crawler = new crawler();
    if($_GET['b'] == "partial"){//update 3 sites at random
        $result = json_encode($crawler->update());
    }else if($_GET['b'] == "single" && is_numeric($_GET['c'])){//do a single site
        $result = array();
        $result[] = $crawler->singleUpdate($_GET['c']);
        $result[] = $crawler->clearOld();
        $result = json_encode($result);
    }else{//regular update
        $result = json_encode($crawler->fullUpdate());
    }
    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','crawl','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "indexing"){
    //makes indexes articles in the database
    require_once("indexing.php");
    $start = date("Y-m-d H:i:s");
    $indexer = new indexing();
    $result = json_encode($indexer->update());
    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','index','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "keyword_extraction"){
    //Extract all the keywords from the articles
    require_once("keyword_extraction.php");
    $start = date("Y-m-d H:i:s");
    $extractor = new keyword_extraction();
    $result = json_encode($extractor->update());
    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','keyword_extraction','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "perform_matching"){
    //does article <-> user matching
    require_once("perform_matching.php");
    $start = date("Y-m-d H:i:s");
    $matching = new perform_matching();
    if($_GET['b']){//single user only
        $result = json_encode($matching->update($_GET['b']));
    }else{//full update
        $result = json_encode($matching->update());
    }
    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','perform_matching','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "reset_update"){
    //resets timer on one/multiple users so that for the next crawl all
    require_once("users.php");
    $start = date("Y-m-d H:i:s");
    $users = new users();
    if($_GET['b']){//single user only
        $result = json_encode($users->resetUpdate($_GET['b']));
    }else{
        $result = json_encode($users->resetUpdate(null));
    }

    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','reset_update','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "full_reset"){
    //resets all users to standard configuration
    require_once("users.php");
    $start = date("Y-m-d H:i:s");
    $users = new users();
    $result = json_encode($users->fullReset());


    $sql->query("INSERT INTO `" . $config->dbprefix . "logs` (`start`,`run`,`output`) VALUES ('" . $start . "','full_reset','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;

}else if($_GET['a'] == "users") {
    require_once("users.php");
    $users = new users();
    if($_GET['b'] == "logout"){
        $users->logout();
    }

}else{
    //load frontend and user class, frontend class handles the rest
    require_once("frontend.php");
    require_once("users.php");
    $users = new users();
    $frontend = new frontend();
    $frontend->display();
}