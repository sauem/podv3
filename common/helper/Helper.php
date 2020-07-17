<?php
namespace common\helper;

use yii\helpers\ArrayHelper;

class Helper
{
    static function prinf($data){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die;
    }
    static function firstError($model){
        $modelErrs = $model->getFirstErrors();
        foreach ($modelErrs as $err) {
            return $err;
        }
        return "No error founded";
    }
    static function getHost($link){
        $parse = parse_url($link);
        return $parse['host'];
    }

    static function userRole($role){
        return \Yii::$app->user->can(ucfirst($role));
    }
    static function option($option){
        return preg_split("/\r\n|\n|\r/", $option);
    }

    static function caculateDate($start , $end, $number = false){
        $newDate = strtotime("+ $end hour",$start);
        if($number){
            return  $newDate;
        }
        return date('H:i:s - d/m', $newDate);
    }
    static function getCountry($code){
        $country = \Yii::$app->params['country'];
        $country = ArrayHelper::map($country,"code","name");
        return ArrayHelper::getValue($country,$code);
    }
    static function money($number){
        return number_format($number,2,',','.') ."đ";
    }
}