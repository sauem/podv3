<?php

namespace backend\controllers;

use backend\models\ContactsModel;
use backend\models\OrdersItems;
use common\helper\Helper;
use Yii;
use backend\models\OrdersModel;
use backend\models\OrdersSearchModel;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OrdersController implements the CRUD actions for OrdersModel model.
 */
class OrdersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrdersModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single OrdersModel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrdersModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new OrdersModel();

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post(),'')){
            try {
                if($model->save()){
                    $product = Yii::$app->request->post('product');
                    foreach ($product as $k => $item){
                        $product[$k]['contact_id'] =(int)Yii::$app->request->post('contact_id');
                        $product[$k]['order_id'] = $model->id;
                        $product[$k]['product_option'] = Yii::$app->request->post('option') ? Yii::$app->request->post('option') : null;
                        $product[$k]['created_at'] = time();
                        $product[$k]['updated_at'] = time();
                    }

                    Yii::$app->db->createCommand()->batchInsert("orders_items", [
                        'product_sku','qty','contact_id','order_id','product_option','created_at','updated_at'
                    ],$product)->execute();

                    return  [
                        'success' => 1,
                        'msg' => 'HIiI'
                    ];
                }
            }catch (\Exception $e){
                return  [
                    'success' => 0,
                    'msg' => $e->getMessage()
                ];
            }

        }

    }

    /**
     * Updates an existing OrdersModel model.
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
     * Deletes an existing OrdersModel model.
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
     * Finds the OrdersModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrdersModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrdersModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
