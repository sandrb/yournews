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
