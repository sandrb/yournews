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
            if($_GET['a'] == "article" && is_numeric($_GET['b'])){
                $this->showArticle($_GET['b']);
            }else{
                //show news overview
                $this->showOverview($curUser);
            }
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
        $keywords = $sql->fetch_object("SELECT id, keyword, weight FROM " . $config->dbprefix . "user_keywords WHERE user_id = '" .  $curUser->id  . "' ORDER BY weight DESC");
        include("templates/overview.php");
    }

    function showArticle($articleId){
        global $sql;
        global $config;
        $article = $sql->fetch_object_single_row("SELECT
                    " . $config->dbprefix . "articles.*,  " . $config->dbprefix . "input_sites.domain
              FROM 
                    " . $config->dbprefix . "articles," . $config->dbprefix . "input_sites 
              WHERE 
                    " . $config->dbprefix . "articles.id = '" . $sql->mysqli->real_escape_string($articleId) . "' AND 
                    " . $config->dbprefix . "input_sites.id = " . $config->dbprefix . "articles.input_site 
              LIMIT 1");
        
        include("templates/article.php");
    }
}