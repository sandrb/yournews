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

            echo  date("G:i",$realTimestamp) . " | " . $article->id . " | " . $article->title . "<br>";
        }
        ?>
    </div>
    <!-- END content -->
    <!-- BEGIN sidebar -->
    <div id="sidebar">
        <!-- begin search -->
        <form action="#">
            <input type="text" name="s" id="s" value="" />
            <button type="submit">Search</button>
        </form>
        <!-- end search -->
        <!-- begin popular articles -->
        <div class="box">
            <h2>Popular Articles</h2>
            <ul>
                <li><a href="#">Lorem Ipsum Dolor Site Amet Veritas Vos</a></li>
                <li><a href="#">Lorem Ipsum Dolor Site Amet Veritas Vos</a></li>
                <li><a href="#">Lorem Ipsum Dolor Site Amet Veritas Vos</a></li>
                <li><a href="#">Lorem Ipsum Dolor Site Amet Veritas Vos</a></li>
                <li><a href="#">Lorem Ipsum Dolor Site Amet Veritas Vos</a></li>
            </ul>
        </div>
        <!-- end popular articles -->
        <!-- begin flickr rss -->
        <div class="box">
            <h2>Flickr RSS</h2>
            <p class="flickr"> <a href="#"><img src="files/images/thumb02.jpg" alt="" /></a> <a href="#"><img src="files/images/thumb03.jpg" alt="" /></a> <a href="#"><img src="files/images/thumb04.jpg" alt="" /></a> <a href="#"><img src="files/images/thumb05.jpg" alt="" /></a> <a href="#"><img src="files/images/thumb06.jpg" alt="" /></a> <a href="#"><img src="files/images/thumb07.jpg" alt="" /></a> </p>
        </div>
        <!-- end flickr rss -->
        <!-- begin featured video -->
        <div class="box">
            <h2>Featured Video</h2>
            <div class="video"><img src="files/images/video.jpg" alt="" /></div>
        </div>
        <!-- end featured video -->
        <!-- BEGIN left -->
        <div class="l">
            <!-- begin categories -->
            <div class="box">
                <h2>Categories</h2>
                <ul>
                    <li><a href="#">Advertising</a></li>
                    <li><a href="#">Entertainment</a></li>
                    <li><a href="#">Fashion</a></li>
                </ul>
            </div>
            <!-- end categories-->
        </div>
        <!-- END left -->
        <!-- BEGIN right -->
        <div class="r">
            <!-- begin archives -->
            <div class="box">
                <h2>Archives</h2>
                <ul>
                    <li><a href="#">March 2009</a></li>
                    <li><a href="#">February 2009</a></li>
                    <li><a href="#">January 2009</a></li>
                    <li><a href="#">December 2008</a></li>
                </ul>
            </div>
            <!-- end archives -->
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
