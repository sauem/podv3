<?php

namespace backend\controllers;

use backend\models\ContactsLog;
use backend\models\ContactsModel;
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
                'success' => 1,
                'logs' => $logs,
                'selected' => ArrayHelper::getValue(array_shift($logs),'status'),
                'status' => ContactsModel::STATUS
            ];
        }

        return [
            'success' => 1,
            'logs' => [],
            'status' => ContactsModel::STATUS
        ];
    }

    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $log = new ContactsLog();
        if (\Yii::$app->request->isPost && $log->load(\Yii::$app->request->post())) {
            if ($log->save()) {
                self::success('Thêm trạng thái thành công!');
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
}
