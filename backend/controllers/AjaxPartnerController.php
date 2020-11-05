<?php


namespace backend\controllers;


use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use GuzzleHttp\Client;

class AjaxPartnerController extends BaseController
{
    public $sheetID = "1BKMRrB0aPJPJmJZoTrqQbk6hNuuhOHjFezvZdT9aS6c";

    public function init()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    function actionGetWarehouse()
    {
        $partner = \Yii::$app->request->get('partner');
        try {
            $client = static::initClient();
            $service = new \Google_Service_Sheets($client);
            $range = "Data kho!A2:E";

            $response = $service->spreadsheets_values->get($this->sheetID, $range);

            $values = $response->getValues();
            $values = self::group_by(0, $values);
            $values = $values[$partner];
            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return $values;
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    function actionGetOrder()
    {
        $partner = \Yii::$app->request->get('partner');
        try {
            $client = static::initClient();
            $service = new \Google_Service_Sheets($client);

            $range = "Data contact!A2:AR";

            $response = $service->spreadsheets_values->get($this->sheetID, $range);

            $values = $response->getValues();
            $data = self::group_by(12, $values);
            $values = $data[$partner];
            $phone = array_keys(self::group_by(4, $values));
            $C11 = array_keys(self::group_by(43, $values));
            $C8 = array_keys(self::group_by(39, $values));

            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return [
            'data' => $values,
            'filter' => [
                'phone' => $phone,
                'C8' => $C8,
                'C11' => $C11
            ]
        ];
    }


    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    function actionGetSale()
    {
        $dataSet = [];
        $partner = \Yii::$app->request->get('partner');
        try {
            $client = static::initClient();
            $service = new \Google_Service_Sheets($client);

            $range = "Data contact!A2:BG";

            $response = $service->spreadsheets_values->get($this->sheetID, $range);

            $values = $response->getValues();
            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }
            $data = self::group_by(12, $values);
            $partner = $data[$partner];
            $data = self::group_by(2, $partner);
            $product = array_keys(self::group_by(6, $partner));
            $source = array_keys(self::group_by(11, $partner));
            $marketer = array_keys(self::group_by(13, $partner));
            $sale = array_keys(self::group_by(14, $partner));
            $labels = array_keys($data);
            $sumRevenueC8 = $totalC3 = $totalC8 = $totalC11 = $total_C8_C3 = $total_C11_C3 = $total_C11_C8 = 0;

            foreach ($labels as $k => $label) {
                $C3 = ArrayHelper::getColumn($data[$label], 51);
                $C8 = ArrayHelper::getColumn($data[$label], 56);
                $C4 = ArrayHelper::getColumn($data[$label], 52);
                $C6 = ArrayHelper::getColumn($data[$label], 54);
                $C7 = ArrayHelper::getColumn($data[$label], 55);
                $C11 = ArrayHelper::getColumn($data[$label], 57);
                $rev_c8 = ArrayHelper::getColumn($data[$label], 40);

                $sumbC3 = array_sum($C3);
                $sumbC8 = array_sum($C8);
                $sumbC6 = array_sum($C6);
                $sumbC7 = array_sum($C7);
                $sumbC4 = array_sum($C4);
                $sumbC11 = array_sum($C11);
                $sumRevenueC8 = array_sum($rev_c8);

                $dataSet["C3"][$k] = $sumbC3;
                $dataSet["C6"][$k] = $sumbC6;
                $dataSet["C7"][$k] = $sumbC7;
                $dataSet["C4"][$k] = $sumbC4;
                $dataSet["C8"][$k] = $sumbC8;
                $dataSet["C8_C3"][$k] = $sumbC8 > 0 ? round($sumbC8 / $sumbC3 * 100) : 0;
                $dataSet["C11"][$k] = $sumbC11;

                $totalC3 = $totalC3 + $sumbC3;
                $totalC11 = $totalC11 + $sumbC11;
                $totalC8 = $totalC8 + $sumbC8;
                $sumRevenueC8 = $sumRevenueC8 + $sumRevenueC8;
            }
            $total_C8_C3 = $totalC8 > 0 ? round($totalC8 / $totalC3 * 100) : 0;
            $total_C11_C3 = $totalC11 > 0 ? round($totalC11 / $totalC3 * 100) : 0;
            $total_C11_C8 = $totalC11 > 0 ? round($totalC11 / $totalC8 * 100) : 0;

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return [
            'labels' => $labels,
            'dataSet' => $dataSet,
            'calculate' => [
                'revenue_c8' => $sumRevenueC8,
                'C8' => $totalC8,
                'C3' => $totalC3,
                'C11' => $totalC11,
                'C11_C3' => $total_C11_C3,
                'C11_C8' => $total_C11_C8,
                'C8_C3' => $total_C8_C3
            ],
            'filter' => [
                'product' => array_merge(['' => 'Null'], $product),
                'source' => array_merge(['' => 'Null'], $source),
                'marketer' => array_merge(['' => 'Null'], $marketer),
                'page' => [],
                'sale' => array_merge(['' => 'Null'], $sale),
                'date' => null
            ]
        ];
    }

    function actionGetFinance()
    {
        $dataSet = [];
        $partner = \Yii::$app->request->get('partner');
        try {
            $client = static::initClient();
            $service = new \Google_Service_Sheets($client);

            $range = "Data contact!M2:BG";

            $response = $service->spreadsheets_values->get($this->sheetID, $range);

            $values = $response->getValues();
            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }
            $data = self::group_by(12, $values);
            $data = $data[$partner];
            $data = self::group_by(2, $data);
            $labels = array_keys($data);

            foreach ($labels as $k => $label) {
                $C3 = ArrayHelper::getColumn($data[$label], 51);
                $C8 = ArrayHelper::getColumn($data[$label], 56);

                $sumbC3 = array_sum($C3);
                $sumbC8 = array_sum($C8);

                $dataSet["C3"][$k] = $sumbC3;
                $dataSet["C8"][$k] = $sumbC8;
            }

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return [
            'labels' => $labels,
            'dataSet' => $dataSet
        ];
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

    static function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}