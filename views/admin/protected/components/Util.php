<?php
class Util
{
    public static function dateFormat($timestamp) {
        $language = Yii::app()->language;
        if($language == 'fr') {
            $ret = date("d-M-Y", $timestamp);
        }else{
            $ret = date("Y M d", $timestamp);
        }
        return $ret;
    }
}
