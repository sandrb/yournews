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

        //add user
        if($_POST['add_user'] == "Add user"){
            $keywords = explode(" ",$_POST['keywords']);
            if(count($keywords) >= 3 && !empty($_POST['username'])){
                $users->addUser($_POST['username'], $keywords);
            }
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
        global $users;

        if(isset($_POST['keyword'])){
            //add new keyword

            //only take part before space
            $keyword = $_POST['keyword'];
            if(strpos($keyword," ") !== false){
                list($keyword,) = explode(" ", $keyword);
            }
            $users->addKeyword($keyword);
        }

        //die("SELECT id,input_site,url,timestamp,title FROM " . $config->dbprefix . "articles WHERE id IN(SELECT article FROM " . $config->dbprefix . "matches WHERE user = " . $curUser->id . ")");
        $articles = $sql->fetch_object("SELECT id,input_site,url,timestamp,title FROM " . $config->dbprefix . "articles WHERE id IN(SELECT article FROM " . $config->dbprefix . "matches WHERE user = " . $curUser->id . ") ORDER BY timestamp DESC");
        $keywords = $sql->fetch_object("SELECT id, keyword, weight FROM " . $config->dbprefix . "user_keywords WHERE user_id = '" .  $curUser->id  . "' ORDER BY weight DESC");
        include("templates/overview.php");
    }

    function showArticle($articleId){
        global $sql;
        global $config;
        global $users;
        $curUser = $users->curUser();
        $article = $sql->fetch_object_single_row("SELECT
                    " . $config->dbprefix . "articles.*,  " . $config->dbprefix . "input_sites.domain
              FROM 
                    " . $config->dbprefix . "articles," . $config->dbprefix . "input_sites 
              WHERE 
                    " . $config->dbprefix . "articles.id = '" . $sql->mysqli->real_escape_string($articleId) . "' AND 
                    " . $config->dbprefix . "input_sites.id = " . $config->dbprefix . "articles.input_site 
              LIMIT 1");
        $article_keywords = $sql->fetch_object("SELECT keyword FROM " . $config->dbprefix . "article_keywords WHERE article_id = '" . $sql->mysqli->real_escape_string($articleId) . "'");
        $user_keywords = $sql->fetch_object("SELECT keyword FROM " . $config->dbprefix . "user_keywords WHERE user_id = '" . $sql->mysqli->real_escape_string($curUser->id) . "'");
        $user_keywords_array = array();
        foreach($user_keywords as $user_keyword){
            $user_keywords_array[] = $user_keyword->keyword;
        }
        include("templates/article.php");
        flush();//send output to user and then do keyword updates in the background
        $users->improve_match($curUser->id,$articleId);
    }
}