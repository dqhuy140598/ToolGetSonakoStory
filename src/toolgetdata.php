<?php
/**
 * Created by PhpStorm.
 * User: Dao Quang Huy
 * Date: 27/11/2018
 * Time: 9:19 SA
 */
        include "lib/Curl/ArrayUtil.php";
        include "lib/Curl/CaseInsensitiveArray.php";
        include "lib/Curl/Curl.php";
        include "lib/Curl/Decoder.php";
        include "lib/Curl/Encoder.php";
        include "lib/Curl/MultiCurl.php";
        include "lib/Curl/StringUtil.php";
        include "lib/Curl/Url.php";
        include "lib/DiDom/ClassAttribute.php";
        include "lib/DiDom/Document.php";
        include "lib/DiDom/Element.php";
        include "lib/DiDom/Encoder.php";
        include "lib/DiDom/Errors.php";
        include "lib/DiDom/Query.php";
        include "lib/DiDom/StyleAttribute.php";
        use \Curl\Curl;
        use \DiDom\Document;

        /**
         * @param $url url của trang web cần lấy mã nguồn
         * @return mixed|null  trả về mã nguồn của trang web được load url
         * @throws ErrorException   Ngoại lễ sinh ra lỗi
         */
        function loadURL($url){
            $curl = new Curl();
            $curl->setConnectTimeout(60);
            $curl->setTimeout(10);
            if($curl->error){
                return null;
            }
            $html = $curl->get($url);
            $curl->close();
            return $html;
        }

        /**
         * @param $url url tới nguồn của file cần tải về
         * @param $saveto đường dẫn tới chỗ lưu tệp tải về và tên của tệp đó
         * @return bool true nếu tải về thành công và false nếu tải về thất bại
         * @throws ErrorException   Ngoại lệ sinh ra lỗi
         */
        function downloadFile($url,$saveto){
            $curl = new Curl();
            $curl->setConnectTimeout(60);
            $curl->setTimeout(60);
            $file = $curl->download($url,$saveto);
            $curl->close();
            return $file;
        }

        /**
         * @param $html mã nguồn của trang chuyện cần lấy nội dung
         * @return array một mảng chứa tên của chuyện và nội dung của chuyện
         */
        function getContent($html){
            $story = array();
            $dom = new Document();
            $dom->load($html);
            $content = $dom->find("div[class=WikiaArticle]")[0]->find('p');
            $name = $dom->find("div[class=page-header__main]")[0]->find('h1')[0]->text();
            $stringContent = "";
            foreach ($content as $value){
                $stringContent.=$value;
            }
            $story["content"]=$stringContent;
            $story["name"]=$name;
            return $story;
        }

        /**
         * @param $html mã nguồn cần trích xuất thông tin về chuyện
         * @return array mảng chứa thông tin của chuyện gồm có tên của chương, thông tin các chương
         */
        function getInfomationStory($html){
            $listStoryChapter = array();
            $dom = new Document();
            $dom->load($html);
            if($dom->has("div[id=mw-content-text]")){
               $tempArray = $dom->find("div[id=mw-content-text]")[0]->find("table");
               for($i=2;$i<count($tempArray);$i++){
                   $chapter = array(
                       "nameChapter" => array(),
                       "chapterList" => array(),
                       "urlChapter"  => array()
                   );
                   $strTmp = $dom->find("div[id=mw-content-text]")[0]->find("table")[$i]->find("h3")[0]->text();
                   $strTmp = str_replace('(Full text)','',$strTmp);
                   $chapter["nameChapter"] = $strTmp;
                   $tempArrayListChapter = $dom->find("div[id=mw-content-text]")[0]->find("table")[$i]->find("ul");
                   foreach ($tempArrayListChapter as $value){
                       $arrayTmp = array();
                       $arrayTmp2 = array();
                       $oneChapter = $value->find("li");
                       $oneUrl = $value->find('a');
                       foreach ($oneChapter as $value1){
                           $arrayTmp[] = $value1->text();
                       }
                       foreach ($oneUrl as $value2){
                           $arrayTmp2[] = $value2->getAttribute("href");
                       }
                       $chapter["chapterList"]=$arrayTmp;
                       $arrayTempUrl = array();
                       foreach ($arrayTmp2 as $value){
                           $arrayTempUrl[] = "http://sonako.wikia.com".$value;
                       }
                       $chapter["urlChapter"]= $arrayTempUrl;
                   }
                   $listStoryChapter[] = $chapter;
               }
               return $listStoryChapter;
            }
            else {
                echo "Error to get Story";
                return bull;
            }
        }

