<?php
/**
 * Created by PhpStorm.
 * User: Dao Quang Huy
 * Date: 28/11/2018
 * Time: 8:55 SA
 */

include "Connection.php";
include "toolgetdata.php";
class SonakoStory
{
    private $database;
    private $url;

    /**
     * SonakoStory constructor. phương thức khởi tạo cho class SonakoStory
     * @param $url  url của trang web Sonako
     * @param $database là cơ sở dữ liệu dùng để lưu trữ truyện
     */
    function __construct($url,$database)
    {
        $this->url = $url;
        $this->database = $database;
    }

    /** phương thức lấy thông tin của truyện(tên, số chương) rồi chuyển vào trong database
     * @return bool trả về true nếu lấy thành công và false nếu thất bại
     */
    function getInforStory(){
        try {
            $html = loadURL($this->url);
            $listStory = getInfomationStory($html);
            foreach ($listStory as $value){
                $nameChapter = $value["nameChapter"];
                $numberOfChapter = count($value["chapterList"]);
                $insertQuery = "INSERT INTO volume (volume_name, numberofChapter) VALUES ('$nameChapter','$numberOfChapter')";
                $this->database->insertData($insertQuery);
            }
            return true;
        }
        catch (ErrorException $e){
            echo $e->getMessage();
        }
        return false;
    }

    /** phương thức lấy database lưu truyện của Sonako
     * @return PDO trả về database chưa truyện của Sonako
     */
    function getDataBase (){
        return $this->database;
    }

    /** phương thức lấy nội dung của truyện rồi chuyển vào trong database
     * @return bool trả về true nếu lấy thành công và false nếu thất bại
     */
    function getAllContentStory(){
        try {
            $html = loadURL($this->url);
            $listStory = getInfomationStory($html);
            $UrlArr = array();
            foreach ($listStory as $value){
                $oneUrl = array();
                foreach ($value["urlChapter"] as $value1){
                    $oneUrl[] = $value1;
                }
                $UrlArr[] = $oneUrl;
            }
            $volume_id = 1;
            foreach ($UrlArr as $value){
                foreach ($value as $value1){
                    $post = strpos($value1,"#",0);
                    if($post > 0) continue;
                    else{
                        $src = loadURL($value1);
                        $content = getContent($src);
                        $nameChapter = $content["name"];
                        $chapterContent = $content["content"];
                        $chapterContent = str_replace("'","\"",$chapterContent);
                        $query = "INSERT INTO chapter(volume_id,chapter_name,chapter_content) VALUES ('$volume_id','$nameChapter','$chapterContent')";
                        $this->database->insertData($query);
                    }
                }
                $volume_id++;
            }
            return true;
        }
        catch (ErrorException $e){
            echo $e->getMessage();
        }
        return false;
    }

}