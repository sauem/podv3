<?php


namespace backend\controllers;


use backend\jobs\autoBackup;
use backend\jobs\doScanContactByCountry;
use backend\models\Backups;
use backend\models\CategoriesModel;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\Customers;
use backend\models\FormInfo;
use backend\models\FormInfoSku;
use backend\models\LogsImport;
use backend\models\OrdersBilling;
use backend\models\OrdersModel;
use backend\models\ProductsModel;
use backend\models\UserModel;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use Yii;
use backend\models\UploadForm;
use yii\web\UploadedFile;
use backend\models\ImageUpload;
use backend\models\Payment;
use yii2tech\spreadsheet\Spreadsheet;

class AjaxController extends BaseController
{
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        parent::init(); // TODO: Change the autogenerated stub
    }

    function actionApprovePhone()
    {
        $phones = Yii::$app->request->post("phones");
        $user = Yii::$app->request->post("user");
        $userCountry = UserModel::findOne($user);

        if (!empty($phones)) {
            foreach ($phones as $phone) {
                if ($userCountry->country !== $phone['country']) {
                    return [
                        'success' => 0,
                        'msg' => "Tài khoản {$userCountry->username} không cùng thị trường với số điện thoại {$phone['phone']}"
                    ];
                }
                $data = [
                    'contact_phone' => $phone['phone'],
                    'user_id' => (int)$user,
                    'country' => $phone['country'],
                    'status' => ContactsAssignment::_PENDING,
                ];
                $model = ContactsAssignment::findOne(['contact_phone' => $phone]);
                if (!$model) {
                    $model = new ContactsAssignment;
                }
                if ($model->load($data, "")) {
                    if (!$model->save()) {
                        return [
                            'success' => 0,
                            'msg' => Helper::firstError($model)
                        ];
                    }
                }
                ActionLog::add("success", Yii::$app->user->getIdentity()->username . " Phân bổ SDT " . $phone["phone"] . " cho tài khoản " . $model->user->username);
            }
            return [
                'success' => 1
            ];
        }
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

        $phone = ArrayHelper::getValue($contacts[0], 'phone');
        $customer = Customers::findOne(['phone' => $phone]);
        if (!$customer) {
            $customer = $contacts[0];
        }
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

            foreach ($contacts as $k => $contact) {
                $model = new ContactsModel;
                $data = [
                    'phone' => $contact['phone'],
                    'name' => $contact['name'],
                    'address' => $contact['address'],
                    'option' => $contact['option'],
                    'zipcode' => (int)$contact['zipcode'],
                    'note' => $contact['note'],
                    'link' => $contact['link'],
                    'ip' => $contact['ip'],
                    'utm_source' => $contact['utm_source'],
                    'utm_campaign' => $contact['utm_campaign'],
                    'utm_medium' => $contact['utm_medium'],
                    'utm_term' => $contact['utm_term'],
                    'utm_content' => $contact['utm_content'],
                    'country' => $contact['country'],
                    'type' => $contact['type'],
                    'register_time' => (int)$contact['register_time'],
                    'created_at' => time(),
                    'updated_at' => time(),
                    'host' => $contact['host'],
                ];

                if (!$model->load($data, '') || !$model->save()) {
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
                    'totalInsert' => $count,
                    'totalErrors' => sizeof($errors)
                ];
            }
            return [
                'success' => 0,
                'error' => $errors,
                'totalInsert' => $count,
                'totalErrors' => sizeof($errors)
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

    public function actionPushOrder()
    {
        $orders = Yii::$app->request->post("contacts");
        $createNew = Yii::$app->request->post('createNew');
        $fileName = Yii::$app->request->post("fileName");
        $transaction = Yii::$app->db->beginTransaction();
        $createNew = $createNew == "ok";
        $errors = [];
        try {
            foreach ($orders as $k => $data) {
                $model = new FormInfo;
                $category = CategoriesModel::findOne(['name' => $data['category']]);
                if (!$category) {
                    if (!$createNew) {

                        $errors[$k] = "không tồn tại danh mục";
                        continue;
                    }
                    $category = new CategoriesModel;
                    $category->name = $data['category'];
                    $category->save();
                }
                $info = [
                    'category_id' => $category->id,
                    'content' => $data['content'],
                    'revenue' => $data['revenue']
                ];
                if ($model->load($info, "") && $model->save()) {
                    $skus = ArrayHelper::getValue($data, 'skus');
                    foreach ($skus as $sku) {

                        $product = ProductsModel::findOne(['sku' => $sku['sku']]);
                        if (!$product) {
                            if (!$createNew) {
                                $errors[$k] = "không tồn tại mã sản phẩm";
                                continue;
                            }
                            $product = new ProductsModel;
                            $product->name = $sku['sku'];
                            $product->category_id = $category->id;
                            $product->sku = $sku['sku'];
                            $product->save();
                        }

                        if ($product->sku && $sku['qty']) {
                            $item = new FormInfoSku;
                            $item->info_id = $model->id;
                            $item->sku = $product->sku;
                            $item->qty = $sku['qty'];
                            $item->save();
                        }
                    }
                } else {
                    return [
                        'success' => 0,
                        'msg' => Helper::firstError($model)
                    ];
                }
            }

            $transaction->commit();
            $count = sizeof($orders);
            ActionLog::add("success", "Nhập file mẫu đơn - $fileName số lượng $count");
            return static::resultImport($count, $errors, 1);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'success' => 0,
                'msg' => $e->getMessage()
            ];
        }
    }

    static function resultImport($count = 0, $errors, $status = 1)
    {
        return [
            'success' => $status,
            'error' => $errors,
            'totalErrors' => sizeof($errors),
            'totalInsert' => $count
        ];
    }

    function actionRemoveImage()
    {
        $names = Yii::$app->request->post('images');

        foreach ($names as $name) {
            $exit = OrdersBilling::findOne(['path' => $name, 'active' => 'active']);
            if ($exit) {
                continue;
            }
            unlink(Yii::getAlias("@files") . "/$name");
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
            ActionLog::add("success", Yii::$app->user->getIdentity()->username . ($type == "open" ? "Mở chỉnh sửa " : " khóa chỉnh sửa")) . " đơn hàng $key";
            return [
                'success' => 1,
            ];
        }
        return [
            'success' => 0,
            'msg' => Helper::firstError($order)
        ];
    }

    function actionOrderData()
    {
        $key = Yii::$app->request->post("key");

        $order = OrdersModel::find()->where(['id' => $key])
            ->with('items')
            ->with('contacts')
            ->with('billings')
            ->asArray()->one();
        if (!$order) {
            return [
                'success' => 0,
                'msg' => 'Không tồn tại đơn hàng này!',
            ];
        }
        $payment = Payment::find()->with('infos')->all();
        $countries = Yii::$app->params['country'];
        $skus = ProductsModel::find()->distinct('sku')->asArray()->all();

        $items = $order['items'];
        foreach ($items as $k => $item) {
            $items[$k]['product']['option'] = Helper::option($item['product']['option']);
            $items[$k]['product']['selected'] = $item['product_option'];
        }
        $order['items'] = $items;

        $path = null;
        if ($order['billings']) {
            $path = ArrayHelper::getColumn($order['billings'], 'path');
        }
        return [
            'success' => 1,
            'items' => ArrayHelper::getValue($order, 'items'),
            'skus' => $skus,
            'customer' => [
                'info' => $order,
                'payment' => $payment,
                'countries' => $countries,
                'path' => $path
            ],
        ];
    }

    function actionOrderStatus()
    {
        $key = Yii::$app->request->post('key');
        $status = Yii::$app->request->post('status');

        $model = OrdersModel::findOne($key);
        if (!$model) {
            return [
                'success' => 0,
                'msg' => "Đơn hàng không tồn tại"
            ];
        }
        $model->status = $status;
        if ($model->save()) {
            return [
                'success' => 1,
                'msg' => 'Thay đổi trạng thái thành công!'
            ];
        }
        return [
            'success' => 0,
            'msg' => Helper::firstError($model)
        ];
    }

    function actionReloadBackup()
    {
        $command = autoBackup::save();

        exec($command['command'], $output, $return_var);
        $saveDB = new Backups;
        if (!$return_var) {
            autoBackup::pushDriver($command['path']);
            $saveDB->name = basename($command['path']);
            $saveDB->save();
            return [
                'success' => 1,
                'msg' => 'Cập nhật dữ liệu thành công! ' . $return_var
            ];
        }
        return [
            'success' => 0,
            'msg' => 'Lỗi hệ thống!' . Helper::firstError($saveDB)
        ];
    }

    function actionRemoveHistoryImport()
    {
        if (Yii::$app->request->isPost) {
            $logs = LogsImport::deleteAll();
            if ($logs) {
                return [
                    'success' => 0
                ];
            }
            return [
                'success' => 1,
                'msg' => Helper::firstError($logs)
            ];
        }
    }

    function actionRemoveHistorySystem()
    {
        if (Yii::$app->request->isPost) {
            $logs = ActionLog::deleteAll();
            if ($logs) {
                return [
                    'success' => 1
                ];
            }
            return [
                'success' => 0,
                'msg' => Helper::firstError($logs)
            ];
        }
    }

    function actionScanContact()
    {
        if (Yii::$app->request->isPost) {
            return doScanContactByCountry::apply();
        }
    }

    function actionDeleteAll()
    {
        if (Yii::$app->request->isAjax) {
            $model = Yii::$app->request->post('model');
            $keys = Yii::$app->request->post('keys');
            $res = $model::deleteAll(['id' => $keys]);
            if ($res) {
                return [
                    'success' => 1,
                    'msg' => 'Xóa thành công!'
                ];
            }
            return [
                'success' => 0,
                'msg' => Helper::firstError($model)
            ];
        }
    }

    function actionFormInfo()
    {
        if (Yii::$app->request->isAjax) {
            $transaction = Yii::$app->db->beginTransaction();
            $model = new FormInfo;
            try {
                $data = Yii::$app->request->post();
                $formId = ArrayHelper::getValue($data, "info_id");
                if ($formId) {
                    $model = FormInfo::findOne($formId);
                }
                $model->load($data, 'FormInfo');
                if ($model->save()) {
                    $info = ArrayHelper::getValue($data, 'info');
                    if ($info && count($info) > 0) {
                        if (!$model->isNewRecord) {
                            FormInfoSku::deleteAll(['info_id' => $model->id]);
                        }
                        foreach ($info as $k => $sku) {
                            if (!$sku['qty']) {
                                continue;
                            }
                            $item = new FormInfoSku;
                            $item->info_id = $model->id;
                            $item->sku = $sku['sku'];
                            $item->qty = $sku['qty'];

                            if (!$item->save()) {
                                throw new \Exception('Failed to save model 2');
                            }
                        }
                    } else {
                        throw new \Exception('Failed to save model 1');
                    }
                    $transaction->commit();
                    return [
                        'success' => 1,
                        'msg' => "Tạo thành công!"
                    ];
                }


            } catch (\Exception $e) {
                $transaction->rollBack();
            }
            return [
                'success' => 0,
                'msg' => Helper::firstError($model)
            ];
        }
    }

    function actionFindFormInfo()
    {
        if (Yii::$app->request->isPost) {
            $option = Yii::$app->request->post("option");
            $category = Yii::$app->request->post("category");
            if (!$option) {
                return [
                    'success' => 0,
                    'msg' => 'Không mẫu sản phẩm phù hợp'
                ];
            }
            $model = FormInfo::find()
                ->with('skus')
                ->where(['LIKE', 'content', $option])
                ->andWhere(['category_id' => $category])->all();

            if ($model) {
                $data = [];
                $base = [];
                foreach ($model as $k => $info) {
                    $base[$k] = [
                        'category' => $info->category->name,
                        'revenue' => $info->revenue,
                        'content' => $info->content,
                        'skus' => []
                    ];
                    $data[$k]['total'] = $info->revenue;
                    foreach ($info->skus as $p => $product) {
                        $data[$k]['product'][$p]['category'] = $info->category->name;
                        $data[$k]['product'][$p]['name'] = $product->product->name;
                        $data[$k]['product'][$p]['option'] = [];
                        $data[$k]['product'][$p]['price'] = 0;
                        $data[$k]['product'][$p]['qty'] = $product->qty;
                        $data[$k]['product'][$p]['selected'] = $info->content;
                        $data[$k]['product'][$p]['sku'] = $product->sku;
                        $base[$k]['skus'][$p] = "$product->qty * $product->sku";
                    }
                }
                return [
                    'success' => 1,
                    'data' => $data,
                    'base' => $base,
                    'msg' => "Phát hiện " . count($data) . " mẫu đơn hàng phù hợp!"
                ];
            }
            return [
                'success' => 0,
                'msg' => 'Không có mẫu đơn hàng nào phù hợp!'
            ];
        }
    }

    function actionExportWaitInfo()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $export = new Spreadsheet();

            $contents = ContactsModel::find()
                ->with('page')
                ->with('formInfo')
                ->where(['<>', 'option', ''])
                ->groupBy('option')
                ->asArray()
                ->all();
            if (!$contents) {
                return [
                    'success' => 0,
                    'msg' => 'Không có giá trị nào phù hợp'
                ];
            }
            $data = [];

            $export->renderCell("A1", "Danh mục");
            $export->renderCell("B1", "Nội dung");
            $export->renderCell("C1", "Doanh thu");
            $export->renderCell("D1", "Sku1");
            $export->renderCell("E1", "Qty1");
            $export->renderCell("F1", "Sku2");
            $export->renderCell("G1", "Qty2");
            $export->renderCell("H1", "Sku3");
            $export->renderCell("I1", "Qty3");
            $export->renderCell("J1", "Sku4");
            $export->renderCell("K1", "Qty4");
            $export->renderCell("L1", "Sku5");
            $export->renderCell("M1", "Qty5");

            foreach ($contents as $k => $content) {
                if ($content['option'] === $content['formInfo']['content']) {
                    continue;
                }
                $category = ArrayHelper::getValue($content,'page.category.name',"");
                $data[$k] = [
                    'category' => $category,
                    'content' => $content['option'],
                    'revenue' => null,
                    'sku1' => null,
                    'qty1' => null,
                    'sku2' => null,
                    'qty2' => null,
                    'sku3' => null,
                    'qty3' => null,
                    'sku4' => null,
                    'qty4' => null,
                    'sku5' => null,
                    'qty5' => null,
                ];
            }
            if (empty($data)) {
                return [
                    'success' => 0,
                    'msg' => 'Không có giá trị nào phù hợp'
                ];
            }
            $export->configure([
                'title' => 'order',
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $data
                ]),
            ]);
            $export->startRowIndex = 2;
            $export->showHeader = false;
            foreach (range("A", "M") as $col) {
                $with = 10;
                if (in_array($col, ['A', 'C'])) {
                    $with = 20;
                }
                if ($col == "B") {
                    $with = 30;
                }
                $export->getDocument()->getActiveSheet()->getColumnDimension($col)->setWidth($with);
            }
            $maxRow = sizeof($data) + 1;

            $export->getDocument()->getActiveSheet()
                ->getStyle("B2:B$maxRow")
                ->getAlignment()
                ->setWrapText(true);

            $export->render();
            $export->save("file/form_info.xlsx");
            return [
                'success' => 1,
                'file' => "/file/form_info.xlsx"
            ];
        }
    }
}