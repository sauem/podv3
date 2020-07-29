<?php

namespace backend\controllers;

use backend\models\ContactsModel;
use backend\models\OrdersBilling;
use backend\models\OrdersItems;
use cakebake\actionlog\model\ActionLog;
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
        $post = Yii::$app->request->post();
        $product = ArrayHelper::getValue($post, "product");
        $path  = ArrayHelper::getValue($post,'bills');
        $path = explode(',',$path);

        if (Yii::$app->request->isPost && $model->load($post, '')) {
            try {
                if ($model->save()) {
                    foreach ($product as $k => $item) {

                        $p['order_id'] = $model->id;
                        $p['price'] = $item['price'];
                        $p['product_sku'] = $item['product_sku'];
                        $p['product_option'] = $item['product_option'];

                        $items = new OrdersItems;
                        if ($items->load($p, "") && $items->save()) {
                            continue;
                        } else {
                            return [
                                'success' => 0,
                                'msg' => Helper::firstError($items)
                            ];
                        }
                    }
                    OrdersBilling::updateAll([
                        'order_id' => $model->id,
                        'active' => OrdersBilling::ACTIVE
                    ],['IN','path' ,  $path]);
                    ActionLog::add("success", "Tạo đơn hàng mới $model->id");
                    $msg = ContactsModel::updateCompleteAndNextProcess();
                    return [
                        'success' => 1,
                        'msg' => $msg
                    ];
                }
            } catch (\Exception $e) {
                ActionLog::add("error", "Tạo đơn hàng thất bại " . $e->getMessage());
                return [
                    'success' => 0,
                    'msg' => $e->getMessage()
                ];
            }

        }

        return [
            'success' => 0,
            'msg' => Helper::firstError($model)
        ];

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
