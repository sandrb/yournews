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
error_reporting(E_ERROR | E_PARSE);

//sql connection
require_once("sql.php");
global $sql;
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

//pageload
if($_GET['a'] == "crawl"){
    require_once("crawler.php");
    $crawler = new crawler($sql);
    $crawler->update();
}else if($_GET['a'] == "admin") {
    include("admin/index.php");
}else{
    echo "Not yet implemented";
}