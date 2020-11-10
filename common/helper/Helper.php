<?php

namespace common\helper;

use backend\models\Backups;
use backend\models\LandingPages;
use backend\models\UserModel;
use backend\models\ZipcodeCountry;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;
use yii2mod\settings\models\SettingModel;

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
        return isset($parse['host']) ?
            preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $parse['host']) :
            preg_replace('#^(http(s)?://)?w{3}\.#', '$1', $link);
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
        if (!$ipdat) {
            return "";
        }
        return $ipdat->geoplugin_countryCode;
    }

    static function findCountryFromZipcode($code, $link = null)
    {
        $country = ZipcodeCountry::findOne(['zipcode' => $code]);
        $land = LandingPages::findOne(['link' => $link]);
        if ($land) {
            return $land->country;
        }
        if ($country) {
            return $country->country_code;
        }
        return false;
    }

    static function convertTime($date)
    {
        if (!is_numeric($date)) {
            return strtotime($date);
        }
        return $date;
    }

    static function getTimeLeft()
    {
        $endDay = strtotime("today 23:59:59");
        return $endDay;
    }

    static function checkEmpty($val)
    {
        return empty($val) || !isset($val) || $val === "";
    }

    static function makeCodeIncrement($lastID, $country = "VN")
    {
        $defaultCode = "#CC" . $country . "0000000";
        $maxLen = strlen($lastID);
        $code = substr_replace($defaultCode, $lastID, -$maxLen);
        return $code;
    }

    static function getDBName()
    {
        preg_match("/dbname=([^;]*)/", Yii::$app->db->dsn, $matches);
        return $matches[1];
    }

    static function setting($name)
    {
        $bk = SettingModel::findOne(['section' => "Common", "key" => $name]);
        return ArrayHelper::getValue($bk, 'value');
    }

    static function getState($code, $city)
    {
        $apiKEY = Helper::setting("map_api");
        $geocode = "https://maps.googleapis.com/maps/api/geocode/json?address=$code,$city&key=$apiKEY";
        // $content = file_get_contents()
    }

    static function link($page)
    {
        if (strpos($page, "http://") !== false) {
            return $page;
        }
        return "http://" . $page;
    }

    static function isAdmin()
    {
        return Helper::userRole(UserModel::_ADMIN);
    }

    static function isMarketing()
    {
        return Helper::userRole(UserModel::_MARKETING);
    }

    static function isSale()
    {
        return Helper::userRole(UserModel::_SALE);
    }

    static function getCur($code = 'VN')
    {
        $country = Yii::$app->params['country'];
        $position = array_search($code, array_column($country, 'code'), true);
        return isset($country[$position]['cur']) ? $country[$position]['cur'] : 'đ';
    }

    static function showMessage($msg, $type = "success")
    {

        if ($type === "success") {
            return Yii::$app->getView()->registerJs("toastr.success('$msg')", 4, rand(0, 10));
        } else {
            return Yii::$app->getView()->registerJs("toastr.warning('$msg')", 4, rand(0, 10));
        }
    }
    static function toLower($str){
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}