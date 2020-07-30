<?php

namespace common\helper;

use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

class Helper
{
    static function prinf($data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die;
    }

    static function firstError($model)
    {
        $modelErrs = $model->getFirstErrors();
        foreach ($modelErrs as $err) {
            return $err;
        }
        return "No error founded";
    }

    static function getHost($link)
    {
        $parse = parse_url($link);
        return isset($parse['host']) ? $parse['host'] : $link;
    }

    static function userRole($role)
    {
        return \Yii::$app->user->can(ucfirst($role));
    }

    static function option($option)
    {
        return preg_split("/\r\n|\n|\r/", $option);
    }

    static function caculateDate($start, $end, $number = false)
    {
        $newDate = strtotime("+ $end hour", $start);
        if ($number) {
            return $newDate;
        }
        return date('H:i:s - d/m', $newDate);
    }

    static function getCountry($code)
    {
        $country = \Yii::$app->params['country'];
        $country = ArrayHelper::map($country, "code", "name");
        return ArrayHelper::getValue($country, $code);
    }

    static function money($number)
    {
        return number_format($number, 2, '.', ',');
    }

    static function toDate($number, $format = "H:i:s d/m")
    {
        return date($format, $number);
    }

    static function getImage($name)
    {
        return Url::to("/file/$name");
    }

    static function formatExcel($num)
    {
        return number_format($num, 2, '.', ',');
    }

    static function countryFromIP($ip)
    {
        $ipdat = @json_decode(file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip));
        if(!$ipdat){
            return "-unknow-";
        }
        return $ipdat->geoplugin_countryCode;
    }

    static function convertTime($date){
        if(!is_numeric($date)){
            return strtotime($date);
        }
        return $date;
    }

    static function getTimeLeft(){
        $endDay = strtotime("today 23:59:59");
        return  $endDay ;
    }
}