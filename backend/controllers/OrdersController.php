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
        $path = ArrayHelper::getValue($post, 'bills');
        $path = explode(',', $path);

        if (Yii::$app->request->isPost && $model->load($post, '')) {
            try {
                if ($model->save()) {
                    foreach ($product as $k => $item) {

                        $p = [
                            'order_id' => $model->id,
                            'price' => $item['price'],
                            'product_sku' => $item['product_sku'],
                            'product_option' => isset( $item['product_option']) ?  $item['product_option'] : null
                        ];

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
                    ], ['IN', 'path', $path]);
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
    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post("order_id");

        $model = OrdersModel::findOne($id);
        if (!$model) {
            return [
                'success' => 0,
                'msg' => 'Đơn hàng không tồn tại'
            ];
        }

        $post = Yii::$app->request->post();
        $product = ArrayHelper::getValue($post, "product");
        $path = ArrayHelper::getValue($post, 'bills');
        $path = explode(',', $path);
        $curentSku = ArrayHelper::getColumn($product,'product_sku');

        if (Yii::$app->request->isPost && $model->load($post, '')) {
            try {
                if ($model->save()) {
                    foreach ($product as $k => $item) {
                        $p = [
                          'order_id' => $model->id,
                          'price' => $item['price'],
                          'product_sku' => $item['product_sku'],
                          'product_option' => isset( $item['product_option']) ?  $item['product_option'] : null
                        ];

                        $items = OrdersItems::findOne(['order_id' => $model->id,
                            'product_sku' => $item['product_sku']]);
                        if(!$items){
                            $items = new OrdersItems;
                        }
                        if ($items->load($p, "") && $items->save()) {
                            continue;
                        } else {
                            return [
                                'success' => 0,
                                'msg' => Helper::firstError($items)
                            ];
                        }
                    }
                    //xóa các sản phẩm đã loại bỏ
                    $condition =  [
                        'AND',['NOT',['product_sku' => $curentSku]],
                        ['order_id' => $model->id]
                    ];
                    OrdersItems::deleteAll($condition);
                    // cập nhật lại hình ảnh hóa đơn thanh toán

                    OrdersBilling::updateAll([
                        'order_id' => $model->id,
                        'active' => OrdersBilling::ACTIVE
                    ], ['IN', 'path', $path]);

                    OrdersBilling::deleteAll([
                       'AND', ['NOT', ['path' => $path]],
                        ['order_id' => $model->id]
                        ]);

                    ActionLog::add("success", "Cập nhật đơn hàng $model->id");
                    return [
                        'success' => 1,
                        'msg' => 'Cập nhật đơn hàng thành công!'
                    ];
                }
            } catch (\Exception $e) {
                ActionLog::add("error", "Cập nhật đơn hàng thất bại " . $e->getMessage());
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
