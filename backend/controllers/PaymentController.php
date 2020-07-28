<?php

namespace backend\controllers;

use backend\models\PaymentInfo;
use common\helper\Helper;
use Yii;
use backend\models\Payment;
use backend\models\PaymentSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends BaseController
{

    public function actionIndex($id = null)
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Payment;
        if($id){
            $model = Payment::findOne($id);
        }
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            if($model->save()){
                self::success("Tạo mới phương thức thanh toán thành công!");
            }else{
                self::error(Helper::firstError($model));
            }
           return $this->refresh();
        }
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $key = null)
    {
        $payment = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => PaymentInfo::find()->where(['payment_id' => $id]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $model = new PaymentInfo;
        if($key){
            $model = PaymentInfo::findOne($key);
        }
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            if($model->save()){
                self::success("Tạo mới phương thức thanh toán thành công!");
            }else{
                self::error(Helper::firstError($model));
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'model' => $model,
            'payment' => $payment,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
