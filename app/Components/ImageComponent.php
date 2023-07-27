<?php
namespace App\Components;
Class ImageComponent
{
    public function checkImg($url,$image_name,$direction){
        if (!file_exists( public_path('/image/'.$direction).$image_name)){
            $sourceimg=$this->GetImageFromUrl("https://aveldent.ru/image/".$url);
            $savefile = fopen(public_path('/image/'.$direction).$image_name, 'w');
            fwrite($savefile, $sourceimg);
            fclose($savefile);
        }
    }
    public function GetImageFromUrl($link) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
