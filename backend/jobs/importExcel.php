<?php


namespace backend\jobs;


use backend\models\ContactsModel;
use backend\models\LogsImport;
use common\helper\Helper;
use yii\base\BaseObject;
use yii\helpers\Console;
use yii\queue\JobInterface;

class importExcel extends BaseObject implements JobInterface
{
    public $data;
    public $fileName;

    public function execute($queue)
    {
        foreach ($this->data as $k => $contact) {
            $model = new ContactsModel;
            $data = [
                'phone' => $contact['phone'],
                'name' => $contact['name'],
                'address' => $contact['address'],
                'option' => $contact['option'],
                'zipcode' => (int)$contact['zipcode'],
                'note' => $contact['note'],
                'link' => $contact['link'],
                'ip' => $contact['ip'],
                'utm_source' => $contact['utm_source'],
                'utm_campaign' => $contact['utm_campaign'],
                'utm_medium' => $contact['utm_medium'],
                'utm_term' => $contact['utm_term'],
                'utm_content' => $contact['utm_content'],
                'type' => $contact['type'],
                'register_time' => (int)$contact['register_time'],
                'host' => $contact['host'],
            ];
            if (!$model->load($data, "") || !$model->save()) {
                $error = [
                    "user_id" => \Yii::$app->user->getId(),
                    "line" => $k,
                    "message" => Helper::firstError($model),
                    "name" => $this->fileName
                ];
                $importLog = new LogsImport;
                $importLog->load($error, "");
                $importLog->save();
            }
            echo Helper::firstError($model) . "\n";
        }

    }
}