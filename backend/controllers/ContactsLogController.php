<?php

namespace backend\controllers;

use backend\models\ContactsLog;
use backend\models\ContactsModel;
use backend\models\UploadForm;
use backend\models\UserModel;
use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ContactsLogController extends BaseController
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = \Yii::$app->request->post();
        $logs = ContactsLog::find()->where(['contact_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->with('contact')->asArray()->all();
        if ($logs) {
            return [
                'key' => $id,
                'success' => 1,
                'logs' => $logs,
                'selected' => ArrayHelper::getValue(array_shift($logs), 'status'),
                'status' => ContactsModel::STATUS
            ];
        }

        return [
            'key' => $id,
            'success' => 1,
            'logs' => [],
            'status' => ContactsModel::STATUS
        ];
    }

    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $log = new ContactsLog();

        if (\Yii::$app->request->isPost) {


            $cids = \Yii::$app->request->post('contact_id');
            $cids = explode(",", $cids);

            if (sizeof($cids) <= 1) {
                if ($log->load(\Yii::$app->request->post(), '') && $log->save()) {
                    return [
                        'success' => 1
                    ];
                }
            } else {
                foreach ($cids as $id) {
                    $log = new ContactsLog();
                    $raw = [
                        'contact_id' => $id,
                        'note' => \Yii::$app->request->post('note'),
                        'status' => \Yii::$app->request->post('status'),
                        'user_id' => \Yii::$app->request->post('user_id')
                    ];
                    $log->load($raw, '');
                    $log->save();
                }
                return [
                    'success' => 1
                ];
            }
            return [
                'success' => 0,
                'msg' => Helper::firstError($log)
            ];
        }

    }

    public function actionStatus()
    {
        $ids = \Yii::$app->request->post('contact_id');
        return $ids;
    }

    public function actionImport()
    {
        $this->layout = "empty";
        $model = new UploadForm;
        return $this->render('import', [
            'model' => $model
        ]);
    }
}
