<?php
/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 24-1-2017
 * Time: 01:34
 */
?>

<html>
    <head>
        <title>Yournews: login</title>
        <link rel="stylesheet" type="text/css" href="files/style.css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="login_form">
                <p class="add_margins bold">Login form</p>
                <form action="" method="POST">
                    User: <select class="add_margins" name="user_id">
                    <?php
                    foreach($logins as $login) {
                        echo '
                        <option value="' . $login->id . '">' .  $login->username . '</option>';
                    }
                    ?>
                    </select> <br>
                    <input type="submit" class="add_margins" name="login" value="Login">
                </form>
            </div>
        </div>
    </body>
</html>