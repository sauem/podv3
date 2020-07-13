<?php

namespace backend\controllers;

use backend\models\ContactsLog;
use common\helper\Helper;
use yii\web\Response;

class ContactsLogController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $log = new ContactsLog();
        if(\Yii::$app->request->isPost && $log->load(\Yii::$app->request->post())){
            if($log->save()){
                self::success('Thêm trạng thái thành công!');
                return [
                    'success' => 1
                ];
            }
            return  [
                'success' => 0,
                'msg' => Helper::firstError($log)
            ];
        }

    }
}
