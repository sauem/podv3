<?php

namespace backend\jobs;

use backend\models\ContactsModel;
use common\helper\Helper;
use GuzzleHttp\Client;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class autoPushSheet
{
    static function push()
    {

            $client = static::initClient();

            $sheets = new \Google_Service_Sheets($client);
            $spreadsheetId = Helper::setting('contact_sheet');

            $data = ContactsModel::find()
                ->with('saleAssign')->with('page')->with('order')->with('contactsLogs')
                ->orderBy([new Expression('
                       CASE WHEN status IS NULL THEN 1 ELSE 0 END,
                       FIELD (status, \'cancel\',\'ok\',\'pending\',\'callback\',\'skip\',\'duplicate\',\'number_fail\')
                       ')])
                ->all();
            $dataService = [];

            if ($data) {
                foreach ($data as $k => $model) {
                    $order = $model->order;
                    $product_sku = "";
                    $product_qty = "";
                    $product_summary = "";
                    $total = "";
                    $shipping_price = "";

                    if ($order) {
                        foreach ($order->items as $item) {
                            $product_sku .= $item->product_sku . ",";
                            $product_qty .= $item->qty . ",";
                            $product_summary .= $item->qty . "*" . $item->product_sku . ",";
                        }
                        $product_sku = substr($product_sku, 0, -1);
                        $product_qty = substr($product_qty, 0, -1);
                        $product_summary = substr($product_summary, 0, -1);
                    }
                    $item = [
                        $model->code,
                        date('d/m/Y H:i:s', $model->register_time),
                        $model->phone,
                        $model->name,
                        $model->address,
                        isset($model->page) ? $model->page->marketer : "",
                        isset($model->saleAssign) ? $model->saleAssign->user->username : "",
                        $model->type,
                        Helper::getCountry($model->country),
                        $model->zipcode,
                        $model->getContactsLogs()->count(),
                        ArrayHelper::getValue(ContactsModel::STATUS, $model->status, ''),
                        $shipping_price,
                        $total,
                        $product_sku,
                        $product_qty,
                        $product_summary
                    ];

                    array_push($dataService, $item);
                }
            }

            //return $dataService;
            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $dataService
            ]);

            $result = $sheets->spreadsheets_values->update($spreadsheetId, 'A2:Q',
                $body, ['valueInputOption' => 'RAW']);
        if(\Yii::$app instanceof \Yii\console\Application){
            return  "Done";
        }
        return $result;
    }

    static function initClient()
    {
        $client = new \Google_Client();
        $client->setClientId(GOOGLE_DRIVE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_DRIVE_CLIENT_SECRET);
        $client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
//        $client->setHttpClient(new Client([
//            'verify' => "D:\cacert.pem"
//        ]));
        return $client;
    }

}