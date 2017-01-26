<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 19-1-2017
 * Time: 23:54
 */
class frontend {
    function display(){
        global $users;
        $curUser = $users->curUser();
        if($curUser == null){
            //show login form
            $this->showLogin();
        }else{
            //show news overview
            $this->showOverview($curUser);
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

    function showOverview($curUser){
        global $sql;
        global $config;
        //die("SELECT id,input_site,url,timestamp,title FROM " . $config->dbprefix . "articles WHERE id IN(SELECT article FROM " . $config->dbprefix . "matches WHERE user = " . $curUser->id . ")");
        $articles = $sql->fetch_object("SELECT id,input_site,url,timestamp,title FROM " . $config->dbprefix . "articles WHERE id IN(SELECT article FROM " . $config->dbprefix . "matches WHERE user = " . $curUser->id . ") ORDER BY timestamp DESC");
        include("templates/overview.php");
    }
}