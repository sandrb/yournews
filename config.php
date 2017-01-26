<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 01:19
 */

class config {
    //local

    public $dbhost = "localhost";
    public $dbuser = "root";
    public $dbpass = "";
    public $dbname = "yournews";

    public $dbprefix = "";

    //percentage of total weight that a word must have to be an article keyword
    //higher value = less keywords per article, lower value = more keywords per article
    public $ratio = 0.03;

    //max weight of all the keywords of a user combined
    public $totalweight = 1000;

    /*
     * each user <-> article combination get's a weight based on the keyword matches
     * here you can specify the minimum weight needed for an article to actually appear in the newsfeed
     */
    public $minweight = 100;

    public $root = "http://localhost/yournews/";

    //online
    /*
    public $dbhost = "db6";
    public $dbuser = "leuk";
    public $dbpass = "S@Nd3r!";
    public $dbname = "leuk";

    public $dbprefix = "yournews_";
    */
}