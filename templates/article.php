<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $article->title ?> | YourNews</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo $config->root ?>/files/style.css" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="<?php echo $config->root ?>files/ie.css" /><![endif]-->
</head>
<body>
<!-- BEGIN wrapper -->
<div id="wrapper">
    <!-- BEGIN header -->
    <div id="header">
        <ul>
            <li><a href="<?php echo $config->root ?>">Home</a></li>
            <li><a href="<?php echo $config->root ?>users/logout">Logout</a></li>
        </ul>
        <h1><a href="#">YourNews</a></h1>
    </div>
    <!-- END header -->
    <!-- BEGIN content -->
    <div id="content">
        <div id="info_block">
            <h3>Article info</h3>
            <b>Title</b>: <?php echo  $article->title; ?><br>
            <?php
                $subdomain = null;
                if(strpos($url,".") !== false){
                    list($subdomain,$url) = explode(".", $url);
                    $subdomain .= ".";
                }
                $url = "http://" . $subdomain . $article->domain . $article->url;
            ?>
            <b>URL</b>: <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a><br>
            <b>Keywords</b>:
            <?php
                foreach($article_keywords as $nr => $article_keyword){
                    if(in_array($article_keyword->keyword,$user_keywords_array)){
                        echo "<b>" . $article_keyword->keyword . "</b>";

                    }else{
                        echo $article_keyword->keyword;
                    }
                    if($nr != sizeof($article_keywords) - 1){
                        echo ", ";
                    }
                }
            ?>
        </div>
        <?php
        echo $article->title . "<p>";
        echo $article->raw_content;
        ?>
    </div>
    <!-- END content -->
    <!-- BEGIN sidebar -->
    <div id="sidebar">
    </div>
    <!-- END sidebar -->
</div>
<!-- END wrapper -->
<!-- BEGIN footer -->
<div id="footer">
    <p>Copyright &copy; <?php echo date("Y"); ?> - YourNews &middot; All Rights Reserved | Template by: <a href="http://www.wpthemedesigner.com/">WordPress Designer</a> </p>
</div>
<!-- END footer -->
</body>
</html>
