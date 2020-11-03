<?php


namespace backend\modules\controllers;


use backend\models\ContactsModel;
use backend\models\OrdersModel;
use common\helper\Helper;
use yii\db\Transaction;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class SheetController extends Controller
{

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new ContactsModel();
        $transaction = \Yii::$app->getDb()->beginTransaction(Transaction::SERIALIZABLE);

        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->getRawBody();
            $data = json_decode($data);
            try {
                if (empty($data) || count($data) <= 1) {
                    throw new BadRequestHttpException('Dữ liệu rỗng!');
                }
                unset($data[0]);

                foreach ($data as $row) {
                    $time = $row[0];
                    $contactData = [
                        'time_register' => strtotime($time),
                        'name' => $row[1],
                        'phone' => $row[2],
                        'address' => $row[3],
                        'category' => $row[4],
                        'option' => $row[5],
                        'note' => $row[6],
                        'zipcode' => $row[7],
                        'country' => strtoupper($row[8]),
                        'type' => $row[9],
                        'link' => $row[12]
                    ];

                    if ($model->load($contactData, '')) {
                        if (!$model->save()) {
                            throw new BadRequestHttpException(Helper::firstError($model));
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new BadRequestHttpException($e->getMessage());
            }
        }
        return $model;
    }
}