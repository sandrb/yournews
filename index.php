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
error_reporting($config->error_reporting);

//sql connection
require_once("sql.php");
global $sql;
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

//pageload
if($_GET['action'] == "crawl"){
    require_once("crawler.php");
    $crawler = new crawler($sql);
    $crawler->update();
}else{
    echo "Not yet implemented";
}