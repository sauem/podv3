<?php
namespace common\helper;

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
}