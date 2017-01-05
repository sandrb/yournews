<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 02:00
 */
class crawler {

    function update(){
        global $sql;
        print_r($sql->fetch_array("SELECT * FROM input_sites"));

        die("todo");
    }
}