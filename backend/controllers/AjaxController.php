<?php


namespace backend\controllers;


use backend\models\CategoriesModel;
use backend\models\ContactsModel;
use backend\models\LogsImport;
use backend\models\OrdersBilling;
use backend\models\OrdersModel;
use backend\models\ProductsModel;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use Yii;
use backend\models\UploadForm;
use yii\web\UploadedFile;
use backend\models\ImageUpload;
use backend\models\Payment;

class AjaxController extends BaseController
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    function actionUploadBill()
    {
        $model = new ImageUpload;
        if (\Yii::$app->request->isPost) {
            $model->billFile = UploadedFile::getInstances($model, 'bill_transfer');
            if ($path = $model->upload()) {
                return [
                    'success' => 1,
                    'path' => $path
                ];
            }
        }
        return [
            'success' => 0,
            'path' => Helper::firstError($model)
        ];

    }

    function actionAjaxFile()
    {
        $model = new UploadForm;
        if (\Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');
            if ($path = $model->upload()) {
                return [
                    'success' => 1,
                    'path' => $path
                ];
            }
        }
        return [
            'success' => 0,
            'path' => null
        ];
    }

    function actionLoadProductSelect()
    {
        $ids = \Yii::$app->request->post('keys');
        $contacts = ContactsModel::find()
            ->with('page')
            ->where(['IN', 'contacts.id', $ids])->asArray()->all();
        $total = array_sum(ArrayHelper::getColumn($contacts, 'page.product.regular_price'));
        $product = ArrayHelper::getColumn($contacts, 'page.product');
        $selected = ArrayHelper::getColumn($contacts, 'option');
        foreach ($product as $k => $p) {
            $product[$k]['option'] = Helper::option($p['option']);
            $product[$k]['selected'] = $selected[$k];
        }
        $customer = $contacts[0];
        $ids = ArrayHelper::getColumn($contacts, 'id');
        $payment = Payment::find()->with('infos')->all();
        $countries = Yii::$app->params['country'];
        return [
            'customer' => [
                'info' => $customer,
                'payment' => $payment,
                'ids' => $ids,
                'countries' => $countries
            ],
            'product' => $product,
            'total' => $total
        ];
    }

    function actionLoadSku()
    {
        $sku = ProductsModel::find()->all();
        return $sku;
    }

    function actionLoadProduct()
    {
        $sku = \Yii::$app->request->post('sku');

        $product = ProductsModel::find()
            ->where(['sku' => $sku])
            ->with('category')
            ->asArray()->one();
        $product['option'] = Helper::option($product['option']);
        return [
            'product' => $product,
            'customer' => [
                'option' => null
            ]
        ];
    }

    function actionUpdateTotal()
    {
        $sku = \Yii::$app->request->post('sku');
        $qty = \Yii::$app->request->post('qty');
        $qty = $qty ? $qty : 1;
        $p = ProductsModel::findOne(['sku' => $sku]);
        $subTotal = $p->regular_price * $qty;
        $saleTotal = $p->sale_price * $qty;
        $total = $subTotal - $saleTotal;
        return [
            'subTotal' => $subTotal,
            'saleTotal' => $saleTotal,
            'total' => $total,
            'qty' => $qty
        ];

    }

    function actionRevenue()
    {
        $kind = \Yii::$app->request->post('kind');
        $time = \Yii::$app->request->post('time');

        $kind = "sale";
        $time = "week";

        $query = OrdersModel::find()->with(['user' => function ($query) {
            $query->select(['user.username', 'user.id']);
        }])->orderBy(['created_at' => SORT_ASC])->groupBy('user_id');

        $result = static::weeklyReport($query);

        $data['label'] = static::labelOfWeek();
        $items = [];

        foreach ($result as $k => $item) {
            $items[$item['user_id']][$k] = static::renderItem($item);
        }
    }

    static function renderItem($item)
    {
        foreach ($item as $k => $val) {
            $data['label'] = $val['name'];
            $data['data'] = $val['total'];
            $data['backgroundColor'] = '';
        }

    }

    static function weeklyReport($query)
    {
        $beginOfDay = strtotime('-7 days');
        $endOfDay = time();
        $query->andFilterWhere([
            'between', 'created_at', $beginOfDay, $endOfDay
        ])
            ->select(['SUM(total) AS `total`'
                , 'DAY(FROM_UNIXTIME(created_at)) as day'
                , 'MONTH(FROM_UNIXTIME(created_at)) as month',
                'user_id'
            ])
            ->groupBy(['day', 'month', 'user_id']);

        return $query->asArray()->all();
    }

    static function labelOfWeek()
    {
        $startWeek = strtotime('this week', time());
        for ($i = 1; $i <= 7; $i++) {
            $label[$i] = date("d/m/Y", strtotime("+$i day", $startWeek));
        }
        return $label;
    }


    public function actionReportSearch()
    {
        $sort = \Yii::$app->request->post();
        $accounts = Yii::$app->request->post("account");
        $time = ArrayHelper::getValue($sort, "created_at");

        $query = OrdersModel::find()
            ->with('user')
            ->with('items')
            ->with('contacts')
            ->orderBy(['created_at' => SORT_DESC]);
        $query->andFilterWhere(['IN', 'user_id', $accounts]);
        if ($time && !empty($time)) {
            $time = explode(" - ", $time);
            $start = strtotime($time[0]);
            $end = strtotime($time[1]);
            if ($start && $end) {
                $query->andFilterWhere(['between', 'created_at', $start, $end]);
            }
        }

        $result = $query->asArray()->all();
        return [
            'success' => 1,
            'data' => $result
        ];
    }


    function actionPushContact()
    {
        $contacts = Yii::$app->request->post("contacts");
        $fileName = Yii::$app->request->post("fileName");
        $errors = [];
        if (!empty($contacts)) {

            foreach ($contacts as $k => $item) {
                $model = new ContactsModel;
                if (!$model->load($item, '') || !$model->save()) {
                    $errors[$k] = Helper::firstError($model);
                    $logs = new LogsImport;
                    $data = [
                        'line' => (string)($k + 2),
                        'message' => Helper::firstError($model),
                        'name' => $fileName,
                        'user_id' => Yii::$app->user->getId()
                    ];
                    $logs->load($data, "");
                    $logs->save();
                }
            }
            $count = sizeof($contacts) - sizeof($errors);
            if ($count > 0) {
                ActionLog::add("success", "Nhập file liên hệ - $fileName số lượng $count");
                return [
                    'success' => 1,
                    'error' => $errors,
                    'totalInsert' => $count
                ];
            }
            return [
                'success' => 0,
                'error' => $errors,
                'totalInsert' => $count
            ];
        }

    }

    function actionPushProduct()
    {
        $products = Yii::$app->request->post("contacts");
        $fileName = Yii::$app->request->post("fileName");
        $errors = [];
        if (!empty($products)) {
            foreach ($products as $k => $product) {
                $model = new ProductsModel;
                $category = CategoriesModel::findOne(['name' => $product['category']]);
                if (!$category) {
                    $category = new CategoriesModel;
                    $category->name = $product['category'];
                    $category->save();
                }
                $data = [
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'category_id' => $category->id,
                    'regular_price' => str_replace(",", "", $product['regular_price']),
                    'option' => $product['option']
                ];

                if (!$model->load($data, "") || !$model->save()) {
                    $errors[$k] = Helper::firstError($model);
                    $logs = new LogsImport;
                    $data = [
                        'line' => (string)($k + 2),
                        'message' => Helper::firstError($model),
                        'name' => $fileName,
                        'user_id' => Yii::$app->user->getId()
                    ];
                    $logs->load($data, "");
                    $logs->save();
                }
            }
            $count = sizeof($products) - sizeof($errors);

            if ($count > 0) {
                ActionLog::add("success", "Nhập file sản phẩm - $fileName số lượng $count");
                return static::resultImport($count, $errors, 1);
            }
            return static::resultImport($count, $errors, 0);
        }

    }

    static function resultImport($count = 0, $errors, $status = 1)
    {
        return [
            'success' => $status,
            'error' => $errors,
            'totalInsert' => $count
        ];
    }

    function actionRemoveImage()
    {
        $names = Yii::$app->request->post('images');
        if (sizeof($names) > 0) {
            foreach ($names as $name) {
                unlink(Yii::getAlias("@files") . "/$name");
            }
            OrdersBilling::deleteAll(['path' => $names]);
        }
        return [
            'success' => 0
        ];
    }

    function actionBlockOrder()
    {
        $key = Yii::$app->request->post("key");
        $type = Yii::$app->request->post("type");
        $order = OrdersModel::findOne($key);
        if (!$order) {
            return [
                'success' => 0,
                'msg' => 'Không tìm thấy đơn hàng này!'
            ];
        }
        $order->block_time = $type == "open" ? Helper::getTimeLeft() : 0;
        $order->admin_block = Yii::$app->user->getId();
        if ($order->save()) {
            ActionLog::add("success",Yii::$app->user->getIdentity()->username . ($type == "open" ? "Mở chỉnh sửa " : " khóa chỉnh sửa")) . " đơn hàng $key";
            return [
                'success' => 1,
            ];
        }
        return [
            'success' => 0,
            'msg' => Helper::firstError($order)
        ];
    }
}