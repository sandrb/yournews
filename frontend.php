<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 19-1-2017
 * Time: 23:54
 */
class frontend {
    function display(){
        session_start();
        if(!isset($_SESSION['userid'])){
            //show login form
            echo "Nog logged in!";
        }else{
            //show news overview or article
            echo "Logged in";
        }
    }
}