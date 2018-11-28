<?php
/**
 * Created by PhpStorm.
 * User: Dao Quang Huy
 * Date: 27/11/2018
 * Time: 11:55 CH
 */

namespace Con;
use PDO;
use PDOException;
class Connection
{
    private $connect;
    function __construct($databaseName)
    {
        try {
            $this->connect = new PDO("mysql:host=localhost;dbname=" . $databaseName, 'root', null);
            $this->connect->exec("set names utf8");
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    function isConnectToDataBase(){
        return $this->connect == null ? false : true;
    }
    function createTable($query){
        try {
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connect->exec($query);
            return true;
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
        return false;
    }
    function getData($query){
        $html = $this->connect->prepare($query);
        $html->execute();
        $html->setFetchMode(PDO::FETCH_ASSOC);
        $result = $html->fetchAll();
        return $result;
    }
    function insertData($query){
        try{
            $this->connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->connect->exec($query);
            return true;
        }
        catch (PDOException $e){
            echo $e->getMessage();
        }
        return false;
    }
    function showTables(){
        $query = "SHOW TABLES";
        $html = $this->connect->prepare($query);
        $html->execute();
        $tables = $html->fetchAll(PDO::FETCH_NUM);
        return $tables;
    }
    function getConnection(){
        return $this->connect;
    }
    function setConnection($con){
        $this->connect = $con;
    }
    function getLastIndexID(){
        return $this->connect->lastInsertId();
    }
}