<?php
    /**
     * Created by PhpStorm.
     * User: Dao Quang Huy
     * Date: 22/11/2018
     * Time: 11:31 CH
     */
    include "SonakoStory.php";
    use Con\Connection;
    set_time_limit(0);
    $url = "http://sonako.wikia.com/wiki/Seirei_Tsukai_no_Blade_Dance";
    $databaseName = "Sonako";
    $connection = new Connection($databaseName);
    if($connection->isConnectToDataBase()){
        $Sonako = new SonakoStory($url,$connection);
        $check = $Sonako->getAllContentStory();
        if($check){
            echo "Success";
        }
        else echo "Fail";

    }
    else echo "Fail";

