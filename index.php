<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 00:19
 */

require_once("config.php");
$config = new config();

require_once("sql.php");
global $sql;
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

if($_GET['action'] == "crawl"){
    require_once("crawler.php");
    $crawler = new crawler($sql);
    $crawler->update();
}else{
    echo "Not yet implemented";
}