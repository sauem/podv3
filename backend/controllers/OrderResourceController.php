<?php

namespace backend\controllers;

use backend\models\OrderResource;
use common\helper\Helper;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class OrderResourceController extends BaseController
{
    public function actionIndex($id = null)
    {
        $model = new OrderResource();
        if ($id) {
            $model = OrderResource::findOne($id);
        }
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                self::success("Tạo mới phương thức thanh toán thành công!");
            } else {
                self::error(Helper::firstError($model));
            }
            return $this->redirect(['index']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => OrderResource::find(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id)
    {
        if ($model = OrderResource::findOne($id)) {
            self::success("Xóa thành công!");
            $model->delete();
            return $this->redirect(['index']);
        }
        throw new NotFoundHttpException('Không tìm thấy bản ghi!');
    }

}
