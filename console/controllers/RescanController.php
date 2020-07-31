<?php

namespace console\controllers;

use backend\jobs\doScanBilling;
use backend\jobs\doScanContact;
use backend\models\ContactsModel;
use common\helper\Helper;

class RescanController extends \yii\console\Controller
{
    public function actionIndex()
    {
        echo doScanContact::apply();
        return 0;
    }
    public function actionImage(){
        echo doScanBilling::scan();
        return 0;
    }
    public function actionFake()
    {
        for ($i = 1; $i < 500; $i++) {
            $phone = rand(1111111111, 9999999999);

            for ($j = 1; $j <= 30; $j++) {
                $data = [
                    'phone' => (string)$phone,
                    'name' => "Nguyễn hoàng " . $i . $j,
                    'address' => 'Hà Nội',
                    'zipcode' => 100000,
                    'type' => 'capture_form',
                    'option' => 'Lựa chọn ' . $i . $j,
                    'link' => 'https://ladi.huynguyen.info',
                    'code' => 'CTVN', //Helper::countryFromIP($_SERVER['REMOTE_ADDR'])
                ];
                $contact = new ContactsModel;
                $contact->load($data, "");
                $res = $contact->save();
                if (!$res) {
                    echo Helper::firstError($contact);
               } else {
                    echo "done $i";
                }

            }
        }

    }
}