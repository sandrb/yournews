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
global $sql;
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

//pageload
if($_GET['a'] == "crawl") {
    //crawls for new articles
    require_once("crawler.php");
    $start = date("Y-m-d H:i:s");
    $crawler = new crawler();
    if($_GET['b'] == "full"){//update all sites
        $result = json_encode($crawler->fullUpdate());
    }else if($_GET['b'] == "single" && is_numeric($_GET['c'])){//do a single site
        $result = array();
        $result[] = $crawler->singleUpdate($_GET['c']);
        $result[] = $crawler->clearOld();
        $result = json_encode($result);
    }else{//regular update
        $result = json_encode($crawler->update());
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

}else if($_GET['a'] == "admin") {
    //admin interface with some database overviews
    require_once("admin.php");
    $admin = new admin();
    $admin->overview();

}else if($_GET['a'] == "phpinfo") {
    //to see current php version, not used anyfurther
    phpinfo();
}else{
    echo "Not yet implemented";
}