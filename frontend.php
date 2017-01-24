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
            $this->showLogin();
        }else{
            //show news overview or article
            echo "Logged in";
        }
    }

    function showLogin(){
        global $users;
        if($_POST['login'] == "Login"){
            echo "login: ";
            print_r($_POST);
        }
        $logins = $users->allUsers();
        include("templates/login.php");
    }
}