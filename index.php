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
    $start = date("Y-m-d H:i:s");
    $crawler = new crawler();
    $result = json_encode($crawler->update());
    $sql->query("INSERT INTO `" . $config->dbprefix . "crawler_log` (`start`,`output`) VALUES ('" . $start . "','" . $sql->mysqli->real_escape_string($result) . "');");
    echo $result;
}else if($_GET['a'] == "admin") {
    require_once("admin.php");
    $admin = new admin();
    $admin->overview();
}else{
    echo "Not yet implemented";
}