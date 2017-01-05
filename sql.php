<?php

/**
 * Created by PhpStorm.
 * User: sbreu
 * Date: 5-1-2017
 * Time: 01:29
 */
class sql{
    public $mysqli;

    function __construct($host,$user,$pass,$dbname) {
        $this->mysqli = new mysqli($host,$user,$pass,$dbname);
        if (!$this->mysqli) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
    }

    /**
     * @param $q input query
     * @return array of the results
     */
    function fetch_array($q){
        if($q = $this->mysqli->query($q)){
            if($q->num_rows == 1){
                $result = $q->fetch_array();
            }else{
                $result = array();
                while($row = $q->fetch_array()){
                    $result[] = $row;
                }
            }
            return $result;
        } else {
            echo "MySQL error: <br>\n";
            var_dump($this->mysqli->error);
            die();
        }
    }

    function __destruct(){
        $this->mysqli->close();
    }
}