<?php


namespace backend\controllers;

use backend\models\ContactsModel;
use cakebake\actionlog\model\ActionLog;
use cakebake\actionlog\model\ActionLogSearch;
use common\helper\Helper;

class SystemLogController extends BaseController
{
    function actionIndex()
    {
        $model = new ActionLogSearch;
        $dataProvider = $model->search(\Yii::$app->request->queryParams);
        return $this->render("index", [
            'searchModel' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}