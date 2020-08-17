<?php

namespace console\controllers;

use backend\jobs\autoBackup;
use backend\jobs\doScanBilling;
use backend\jobs\doScanContactByCountry;
use backend\models\Backups;
use backend\models\ContactsModel;
use common\helper\Helper;

class RescanController extends \yii\console\Controller
{
    public function actionIndex()
    {
        echo doScanContactByCountry::apply();
        return 0;
    }

    public function actionImage()
    {
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

    public function actionBackup()
    {
        $command = autoBackup::save();
        exec($command['command']);
        autoBackup::pushDriver($command['path']);
        $saveDB = new Backups();
        $saveDB->name = basename($command['path']);
        $saveDB->save();

        print ($command['path']);
    }

    static function actionPushDriver($filePath)
    {
        $client = new \Google_Client();
        $client->setClientId(GOOGLE_DRIVE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_DRIVE_CLIENT_SECRET);
        $client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
//        $client->setHttpClient(new Client([
//            'verify' => "D:\cacert.pem"
//        ]));
        $service = new \Google_Service_Drive($client);
        $file = new \Google_Service_Drive_DriveFile();
        $file->setName(basename($filePath));
        $file->setParents([Helper::setting("drive_id")]);
        $service->files->create($file,[
            'data' => file_get_contents($filePath),
            'mimeType' => 'application/sql',
            'uploadType' => 'resumable'
        ]);

    }
}
