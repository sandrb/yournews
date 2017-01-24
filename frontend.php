<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 19-1-2017
 * Time: 23:54
 */
class frontend {
    function display(){
        if(!isset($_SESSION['userid'])){
            //show login form
            $this->showLogin();
        }else{
            //show news overview or article
            $this->showFrontend();
        }
    }

    function showLogin(){
        global $users;
        $showform = true;
        //login attempt
        if($_POST['login'] == "Login" && $users->login($_POST['user_id'])){
            //login is valid, go back to display
            $showform = false;
            $this->display();
        }

        if($showform){
            //no login attempt
            $logins = $users->allUsers();
            include("templates/login.php");
        }
    }

    function showFrontend(){
        include("templates/overview.php");
    }
}