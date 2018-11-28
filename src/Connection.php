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

    /**
     * Connection constructor. hàm khởi tạo cho lớp connection
     * @param $databaseName string chứa tên của database cần kết nối tới
     */
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

    /** phương thức kiểm tra kết nối tới database thành công hay thất bại
     * @return bool trả về true nếu kết nối database thành công và false nếu thất bại
     */
    function isConnectToDataBase(){
        return $this->connect == null ? false : true;
    }

    /** phương thức dùng để tạo bảng trong database
     * @param $query câu lệnh sql dùng để tạo bảng
     * @return bool trả về true nếu tạo bảng thành công và false nếu thất bại
     */
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

    /** phương thức lấy dữ liệu từ database
     * @param $query    câu lệnh sql dùng để lấy dữ liệu từ database
     * @return array    một mảng chứa dữ liệu cần lấy
     */
    function getData($query){
        $html = $this->connect->prepare($query);
        $html->execute();
        $html->setFetchMode(PDO::FETCH_ASSOC);
        $result = $html->fetchAll();
        return $result;
    }

    /** phương thức thêm dữ liệu vào trong database
     * @param $query  câu lệnh sql dùng để thêm dữ liệu vào database
     * @return bool trả về true nếu thêm thành công dữ liệu và false nếu thất bại
     */
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

    /** phương thức lấy ra tên các bảng có trong database
     * @return array trả về một mảng chứa tên của các bảng trong database
     */
    function showTables(){
        $query = "SHOW TABLES";
        $html = $this->connect->prepare($query);
        $html->execute();
        $tables = $html->fetchAll(PDO::FETCH_NUM);
        return $tables;
    }

    /** phương thức get cơ sở dữ liệu
     * @return PDO là cơ sở dữ liệu cần lấy
     */
    function getConnection(){
        return $this->connect;
    }

    /**  phương thức set cơ sở dữ liệu
     * @param $con là cơ sở dữ liệu muốn thay đổi
     */
    function setConnection($con){
        $this->connect = $con;
    }

    /** phương thức lấy khóa cuối cùng được thêm vào database
     * @return string là một xâu chứa khóa cuối cùng được thêm vào database
     */
    function getLastIndexID(){
        return $this->connect->lastInsertId();
    }
}