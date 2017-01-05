<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 00:19
 */

include("config.php");
$config = new config();

include("sql.php");
$sql = new sql($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname);

print_r($sql->fetch_array("SELECT * FROM input_sites"));