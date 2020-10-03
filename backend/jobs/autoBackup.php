<?php


namespace backend\jobs;
use common\helper\Helper;
use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;

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
        $client = static::initDrive();

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
    static function dropDriver($fileName){
        $client = static::initDrive();
        $client->setScopes([\Google_Service_Drive::DRIVE_FILE]);
        $service = new \Google_Service_Drive($client);

        try {
            $files = static::listFile($service);
            $file =  array_filter($files, function ($item) use ($fileName){
                    if($item['name'] == $fileName){
                        return $item['id'];
                    }
            });
            if($file){
                $fileID = ArrayHelper::getValue($file[0],'id');
                $service->files->delete($fileID);
            }

        }catch (\Exception $e){
            return [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }

    }
    static function listFile($service){

        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $service->files->listFiles($optParams);
        $files = $results->getFiles();
        $ids = [];
        if(count($files ) > 0){
           foreach ($files as $k => $file){
               $ids[$k]['id'] = $file->getId();
               $ids[$k]['name'] = $file->getName();
           }
        }
        return $ids;
    }

    static function initDrive(){
        $client = new \Google_Client();
        $client->setClientId(GOOGLE_DRIVE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_DRIVE_CLIENT_SECRET);
        $client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
        $client->setHttpClient(new Client([
            'verify' => "D:\cacert.pem"
        ]));
        return $client;
    }
}