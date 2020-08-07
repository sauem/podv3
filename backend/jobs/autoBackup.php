<?php


namespace backend\jobs;
use common\helper\Helper;
use GuzzleHttp\Client;

class autoBackup
{
    static function save(){
        $fileName = "tcom_".date('d_m_Y_H_i_s') .".sql";
        $command = "mysqldump -u". SQL_USER_NAME ." --password=".SQL_PASSWORD." --default-character-set=utf8".
            " --host=localhost " . Helper::getDBName() .  " > ". \Yii::getAlias("@backups")."/" .$fileName ;
        return [
            "command" => $command,
            "path" => \Yii::getAlias("@backups") . "/" . $fileName
        ];
    }
    static function pushDriver($filePath){
        $client = new \Google_Client();
        $client->setClientId(GOOGLE_DRIVE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_DRIVE_CLIENT_SECRET);
        $client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
        $client->setHttpClient(new Client([
            'verify' => "D:\cacert.pem"
        ]));
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