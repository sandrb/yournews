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
     * Runs a query, fetches the objects and returns them.
     */
    function fetch_object($q){
        if($q = $this->mysqli->query($q)){
            if($q->num_rows == 1){
                $result = $q->fetch_object();
            }else{
                $result = array();
                while($row = $q->fetch_object()){
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



    /**
     * @param $q input query
     * @return the one value
     * Returns the very first value found
     */
    function single_select($q){
        if($q = $this->mysqli->query($q)){
            $result = $q->fetch_array();
            return $result[0];
        } else {
            echo "MySQL error: <br>\n";
            var_dump($this->mysqli->error);
            die();
        }
    }

    /**
     * @param $q input query
     * @return the query result
     * Runs the query and returns it's result
     */
    function query($q){
        if($q = $this->mysqli->query($q)){
            return $q;
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