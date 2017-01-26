<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 19-1-2017
 * Time: 01:29
 */
//note: this is hosted at a DIFFERENT hosting account, this is due to the fact that my own webserver doesn't support cronbjos.
//this script is called once every minute

$root = "http://hlddrole.nl/sander/yournews/"; //url to start with
$request = "";//requested API
$curminutes = (int) date("i");
if($curminutes %3 == 0){
    //initiate partial crawl every 3 minutes
    $request = "crawl/partial";
}else if($curminutes == 1){
    //do indexing once an hour
    $request = "indexing";
}else if($curminutes == 4){
    //do keyword_extraction once an hour after indexing
    $request = "keyword_extraction";

}else if($curminutes == 7){
    //peform matching with updated articles
    $request = "perform_matching";

}else{

    //insert request here to let it occur more often
}

if($request != ""){
    echo $request . "<br>";
    echo file_get_contents($root . $request);
}else{
    echo "no API call";
}