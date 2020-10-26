<?php


namespace backend\controllers;


use backend\models\ArchiveSearch;
use backend\models\Warehouse;
use backend\models\WarehouseSearch;
use backend\models\WarehouseStorage;
use common\helper\Helper;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\models\Archive;

class BillLadingController extends BaseController
{

    //Đơn vị vận chuyển
    public function actionIndex($id = null)
    {

        $searchModel = new ArchiveSearch();
        $model = new Archive();
        if($id){
            $model = Archive::findOne($id);
            if(!$model){
                throw new NotFoundHttpException('Không tồn tại đơn vị vận chuyển!');
            }
        }
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            if (!$model->save()) {
                \Yii::$app->session->setFlash('error', Helper::firstError($model));
            } else {
                \Yii::$app->session->setFlash('success', 'Thao tác thành công!');
            }
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionDeleteDelivery($id){
        $model = Archive::findOne($id);
        if(!$model){
            throw new NotFoundHttpException('Không tồn tại đơn vị vận chuyển!');
        }
        $model->delete();
        \Yii::$app->session->setFlash('success', 'Thao tác thành công!');
        return $this->redirect(['index']);
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
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->delete();
        return $this->redirect(['warehouse']);
    }

    public function actionView($id)
    {
        $model = Warehouse::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $storage = new WarehouseStorage();


        $productStorage = new ActiveDataProvider([
            'query' => WarehouseStorage::find()
                ->where(['warehouse_id' => $id])
                ->with('product'),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('warehouse-view', [
            'model' => $model,
            'storage' => $storage,
            'productStorage' => $productStorage
        ]);
    }

    public function actionSaveStorage()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $storage = new WarehouseStorage();

        if (\Yii::$app->request->isPost && $storage->load(\Yii::$app->request->post())) {
            if (!$storage->save()) {
                throw new BadRequestHttpException(Helper::firstError($storage));
            }
        }
        return true;
    }
}