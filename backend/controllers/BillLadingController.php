<?php


namespace backend\controllers;


use backend\models\Warehouse;
use backend\models\WarehouseSearch;
use common\helper\Helper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class BillLadingController extends BaseController
{

    //Đơn vị vận chuyển
    public function actionIndex()
    {
        $model = new Warehouse();

        return $this->render('index', [
            'model' => $model,

        ]);
    }

    //Kho hàng
    public function actionForm($id = null)
    {
        $this->layout = 'empty';
        $model = new Warehouse();
        if ($id) {
            $model = Warehouse::findOne($id);
        }
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if (!$model->save()) {
                Helper::showMessage(Helper::firstError($model));
            } else {
                Helper::showMessage('Tạo đơn hàng thành công!');
            }
            return $this->redirect(['warehouse']);
        }
        return $this->render('modal/form_warehouse', [
            'model' => $model
        ]);
    }

    public function actionWarehouse()
    {
        $model = new Warehouse();

        $searchModel = new WarehouseSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('warehouse', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionWarehouseDelete($id)
    {
        $model = Warehouse::findOne($id);
        if(!$model){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->delete();
        return $this->redirect(['warehouse']);
    }


}