<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>YourNews</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="files/style.css" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="files/ie.css" /><![endif]-->
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
        <?php
        $drevdate = -1;

        foreach($articles as $article){
            $realTimestamp = strtotime($article->timestamp);
            $date = date("j F",$realTimestamp);
            if($date != $prevdate){
                echo '<p class="details1">' . $date . '</p>';
                $prevdate = $date;
            }

            echo  date("G:i",$realTimestamp) . ' | <a href="' . $config->root . 'article/' . $article->id . '">' . $article->title . '</a><br>';
        }
        ?>
    </div>
    <!-- END content -->
    <!-- BEGIN sidebar -->
    <div id="sidebar">
        <!-- begin popular articles -->
        <div class="box">
            <h2>Your keywords and their weights</h2>
            <ul>
                <?php
                foreach($keywords as $keyword){
                    echo '<li><div class="keyword_entry">' . $keyword->keyword . '</div><div class="keyword_weight"> ' . $keyword->weight . '</div></li>';
                }
                ?>
            </ul>
        </div>
        <!-- END right -->
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
