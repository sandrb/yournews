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
                <div style="height: 50px"></div>
                <p class="add_margins bold">Add user</p>
                <form action="" method="POST">
                    <div class="add_user_div">Username (currently not in use): </div><div class="add_user_div"><input type="text" name="username"></div><br>
                    <div class="add_user_div">Keywords (seperated by a space, at least 3): </div><div class="add_user_div"><input type="text" name="keywords"></div><br>
                    <input type="submit" class="add_margins" name="add_user" value="Add user">
                </form>
            </div>
        </div>
    </body>
</html>