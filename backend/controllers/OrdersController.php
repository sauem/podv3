<?php

namespace backend\controllers;

use backend\models\ContactsModel;
use backend\models\Customers;
use backend\models\OrdersBilling;
use backend\models\OrdersItems;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use Yii;
use backend\models\OrdersModel;
use backend\models\OrdersSearchModel;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
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
    public function _actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        $product = ArrayHelper::getValue($post, "product");
        $path = ArrayHelper::getValue($post, 'bills');
        $path = explode(',', $path);
        $defaultInfo = ArrayHelper::getValue($post, "default_info");

        $model = new OrdersModel();

        if (Yii::$app->request->isPost && $model->load($post, '')) {
            try {
                if ($model->save()) {
                    foreach ($product as $k => $item) {

                        $p = [
                            'order_id' => $model->id,
                            'price' => $item['price'],
                            'product_sku' => $item['product_sku'],
                            'qty' => $item['qty'],
                            'product_option' => isset($item['product_option']) ? $item['product_option'] : null
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
                    //sale image billing
                    OrdersBilling::updateAll([
                        'order_id' => $model->id,
                        'active' => OrdersBilling::ACTIVE
                    ], ['IN', 'path', $path]);
                    //save log create order
                    ActionLog::add("success", "Tạo đơn hàng mới $model->id");
                    //update next phone processing
                    $msg = ContactsModel::updateCompleteAndNextProcess();
                    //save defaultInfo
                    if ($defaultInfo == "on") {
                        $info = self::updateOrCreateCustomer($model);

                        if (!$info) {
                            return [
                                'success' => 1,
                                'msg' => $info
                            ];
                        }
                    }
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
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

        $product = ArrayHelper::getValue($post, "product");
        $path = ArrayHelper::getValue($post, 'bills');
        $path = explode(',', $path);
        $defaultInfo = ArrayHelper::getValue($post, "default_info");

        $transaction = Yii::$app->getDb()->beginTransaction(Transaction::SERIALIZABLE);
        try {
            $model = new OrdersModel();
            if (!$model->load($post, '') || !$model->save()) {
                throw new BadRequestHttpException(Helper::firstError($model));
            }
            if (empty($product)) {
                throw new BadRequestHttpException('Đơn hàng không có sản phẩm!');
            }
            foreach ($product as $k => $item) {
                $orderItems = new OrdersItems;
                $itemOrder = [
                    'order_id' => $model->id,
                    'price' => $item['price'],
                    'product_sku' => $item['product_sku'],
                    'qty' => $item['qty']
                ];
                if (!$orderItems->load($itemOrder, "") || !$orderItems->save()) {
                    throw new BadRequestHttpException(Helper::firstError($orderItems));
                }
            }
//            OrdersBilling::updateAll([
//                'order_id' => $model->id,
//                'active' => OrdersBilling::ACTIVE
//            ], ['IN', 'path', $path]);
//            if ($defaultInfo == "on") {
//                $info = self::updateOrCreateCustomer($model);
//                if (!$info) {
//                    throw new BadRequestHttpException($info);
//                }
//            }
            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new BadRequestHttpException($e->getMessage());
        }

        return [
            'success' => 1,
            'msg' => 'Tạo đơn hàng  thành công!'
        ];
    }

    static function updateOrCreateCustomer(OrdersModel $model)
    {
        $customer = Customers::findOne(['phone' => $model->customer_phone]);
        if (!$customer) {
            $customer = new Customers;
        }
        $info = [
            'name' => $model->customer_name,
            'phone' => $model->customer_phone,
            'email' => $model->customer_email,
            'city' => $model->city,
            'address' => $model->address,
            'district' => $model->district,
            'zipcode' => $model->zipcode,
            'country' => $model->country
        ];

        if ($customer->load($info, "")) {
            $res = $customer->save();
        }
        return $res ? true : Helper::firstError($customer);
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */

    /**
     * Updates an existing OrdersModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $transaction = Yii::$app->getDb()->beginTransaction(Transaction::SERIALIZABLE);
        try {
            $postData = Yii::$app->request->post();
            $order = OrdersModel::findOne($postData['order_id']);
            if (!$order) {
                $order = new OrdersModel();
                //throw new BadRequestHttpException('Không tìm thấy đơn  hàng!');
            }

            if (!$order->load($postData, '') || !$order->save()) {
                throw new BadRequestHttpException(Helper::firstError($order));
            }
            $products = ArrayHelper::getValue($postData, 'product', []);
            if (!$products || empty($products)) {
                throw new BadRequestHttpException("Đơn hàng không có sản phẩm!");
            }
            OrdersItems::deleteAll(['order_id' => $order->id]);

            foreach ($products as $product) {
                $orderItem = new OrdersItems();
                $orderItem->qty = $product['qty'];
                $orderItem->product_sku = $product['product_sku'];
                $orderItem->price = $product['price'];
                $orderItem->order_id = $order->id;
                if (!$orderItem->save()) {
                    throw new BadRequestHttpException("item " . Helper::firstError($orderItem));
                }
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw new BadRequestHttpException($exception->getMessage());
        }
        return [
            'success' => 1,
            'msg' => !$order ?  'Tạo đơn hàng thành công!' : 'Cập nhật đơn hàng thành công!'
        ];
    }

    /**
     * Deletes an existing OrdersModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
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
    protected
    function findModel($id)
    {
        if (($model = OrdersModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
