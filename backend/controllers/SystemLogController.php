<?php


namespace backend\controllers;
use cakebake\actionlog\model\ActionLog;
use cakebake\actionlog\model\ActionLogSearch;

class SystemLogController extends BaseController
{
    function actionIndex(){
        $model = new ActionLogSearch;
        $dataProvider = $model->search(\Yii::$app->request->queryParams);
        return $this->render("index",[
            'searchModel' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}