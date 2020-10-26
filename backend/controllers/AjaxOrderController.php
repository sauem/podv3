<?php


namespace backend\controllers;


use backend\models\ContactsModel;
use backend\models\FormInfo;
use backend\models\OrdersModel;
use backend\models\Payment;
use backend\models\ProductsModel;
use common\helper\Helper;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class AjaxOrderController extends BaseController
{
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */

    public function actionLeadContact()
    {
        $contactId = \Yii::$app->request->post('leadId');
        try {
            $lead = ContactsModel::findOne($contactId);
            if (!$lead) {
                return self::createOrderManual();
            }
            $product = $lead->page->product;

            $payment = Payment::find()->all();
            $countries = \Yii::$app->params['country'];
            $productList = ProductsModel::find()
                ->addSelect(['id', 'category_id', 'name', 'sku'])
                ->with([
                    'category' => function ($query) {
                        $query->select('name,id');
                    }
                ])
                ->asArray()->all();

            $orderExamples = self::getExampleOrder($lead->option, $lead->page->category_id);

            $customer = [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'address' => $lead->address,
                'email' => $lead->email,
                'zipcode' => $lead->zipcode,
                'code' => $lead->code,
                'lead' => $lead->id,
                'country' => $lead->country,
                'note' => $lead->note
            ];
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return [
            'order' => [
                'skuExists' => [$product->sku],
                'customer' => $customer,
                'product' => [
                    [
                        'name' => $product->name,
                        'price' => 0,
                        'qty' => 1,
                        'sku' => $product->sku,
                        'category' => $lead->page->category->name
                    ]
                ],
                'bill' => [],
                'amount' => [
                    'total' => 0,
                    'subTotal' => 0,
                    'shipping' => 0,
                    'currency' => Helper::getCur($lead->country),
                ]
            ],
            'productList' => $productList,
            'orderExample' => $orderExamples,
            'payment' => $payment,
            'countries' => $countries,
        ];
    }

    public static function createOrderManual()
    {
        $payment = Payment::find()->all();
        $countries = \Yii::$app->params['country'];
        $productList = ProductsModel::find()
            ->addSelect(['id', 'category_id', 'name', 'sku'])
            ->with([
                'category' => function ($query) {
                    $query->select('name,id');
                }
            ])
            ->asArray()->all();
        return [
            'order' => [
                'skuExists' => [],
                'customer' => [],
                'product' => [],
                'bill' => [],
                'amount' => [
                    'total' => 0,
                    'subTotal' => 0,
                    'shipping' => 0,
                    'currency' => Helper::getCur('TH'),
                ]
            ],
            'productList' => $productList,
            'orderExample' => [],
            'payment' => $payment,
            'countries' => $countries,
        ];
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionLoadOrder()
    {
        $orderId = \Yii::$app->request->post('orderId');
        $model = OrdersModel::findOne($orderId);
        try {
            if (!$model) {
                throw new BadRequestHttpException("Đơn hàng không tồn tại!");
            }
            $payment = Payment::find()->all();
            $countries = \Yii::$app->params['country'];
            $productList = ProductsModel::find()
                ->addSelect(['id', 'category_id', 'name', 'sku'])->with([
                    'category' => function ($query) {
                        $query->select('name,id');
                    }
                ])->asArray()->all();
            $orderExamples = [];
            if (!Helper::checkEmpty($model->contact)) {
                $orderExamples = self::getExampleOrder($model->contact->contact->option, $model->contact->contact->page->category_id);
            }

            $skuExists = [];
            $products = [];
            if ($model->items) {
                foreach ($model->items as $item) {
                    $skuExists[] = $item->product_sku;
                    $products[] = [
                        'name' => $item->product->name,
                        'price' => $item->price,
                        'qty' => $item->qty,
                        'sku' => $item->product_sku,
                        'category' => $item->product->category->name
                    ];
                }
            }
            $customer = [
                'name' => $model->customer_name,
                'phone' => $model->customer_phone,
                'address' => $model->address,
                'email' => $model->customer_email,
                'zipcode' => $model->zipcode,
                'code' => $model->code,
                'city' => $model->city,
                'district' => $model->district,
                'order_id' => $model->id,
                'country' => $model->country,
                'order_note' => $model->order_note,
                'status_note' => $model->status_note,
                'shipping_price' => $model->shipping_price,
                'payment_method' => $model->payment_method
            ];
            $data = [
                'order' => [
                    'skuExists' => $skuExists,
                    'customer' => $customer,
                    'product' => $products,
                    'bill' => [],
                    'amount' => [
                        'total' => $model->total,
                        'subTotal' => $model->sub_total,
                        'shipping' => $model->shipping_price,
                        'currency' => Helper::getCur($model->country),
                    ]
                ],
                'source_order' => OrdersModel::SOURCE_ORDER,
                'productList' => $productList,
                'orderExample' => $orderExamples,
                'payment' => $payment,
                'countries' => $countries,
            ];
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return $data;
    }

    static function getExampleOrder($content, $category_id)
    {
        $orderExample = FormInfo::find()->where([
            'content' => $content,
            'category_id' => $category_id
        ])->with('category')->with('skus')->all();
        $orderExamples = [];
        foreach ($orderExample as $item) {
            $orderExamples[] = [
                'category' => $item->category->name,
                'content' => $item->content,
                'skus' => ArrayHelper::getColumn($item->skus, 'sku'),
                'id' => $item->id,
                'revenue' => $item->revenue,
            ];
        }
        return $orderExamples;
    }

    public function actionCheckOrderCode()
    {
        $code = \Yii::$app->request->post('code');
        $model = OrdersModel::findOne(['code' => $code]);
        if (!$model) {
            return true;
        }
        throw new BadRequestHttpException('Đã tồn tại mã đơn hàng này!');
    }
}