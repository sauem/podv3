<?php


namespace backend\jobs;
use common\helper\Helper;

class autoBackup
{
    static function save(){
        $fileName = "tcom_".time() .".sql";
        $command = "mysqldump -u". SQL_USER_NAME ." --password=".SQL_PASSWORD." --default-character-set=utf8".
            " --host=localhost " . Helper::getDBName() .  " > ". \Yii::getAlias("@backups")."/" .$fileName ;
        return [
            "command" => $command,
            "path" => \Yii::getAlias("@backups") . "/" . $fileName
        ];
    }
}