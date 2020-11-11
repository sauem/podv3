<?php


namespace backend\controllers;


use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use GuzzleHttp\Client;

class AjaxPartnerController extends BaseController
{
    public $sheetID = "1BKMRrB0aPJPJmJZoTrqQbk6hNuuhOHjFezvZdT9aS6c";
    const PAYED = 'da-thanh-toan';
    const TRANSFERRED = 'da-doi-soat';
    const EXPORT_WAREHOUSE = 'da-xuat-hang';
    const IMPORT_WAREHOUSE = 'nhap';
    const NOT_SHIPPED = 'chua-xuat-hang';
    const INVENTORY = 'ton';
    const BROKEN = 'hong';
    const REFUND = 'hoan';
    const STATUS_OK = 'ok';

    public function init()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::init(); // TODO: Change the autogenerated stub
    }

    function actionSearch()
    {
        $data = \Yii::$app->request->post();
        $base = @json_decode($data['base']);
        $action = $data['action'];
        $filter = $data['filter'];
        return $this->$action($filter, $base);
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
            $productGroup = self::group_by(2, $values);
            $data = [];

            foreach ($productGroup as $category => $value) {
                $importTotal = $exportTotal = $refund = $broken = $inventory = $not_shipped = 0;
                $products = self::group_by(1, $value);
                foreach ($products as $product => $column) {

                    foreach ($column as $key => $val) {
                        $status = Helper::toLower($val[3]);
                        $qty = (int)$val[4];
                        switch ($status) {
                            case self::EXPORT_WAREHOUSE:
                                $exportTotal += $qty;
                                break;
                            case self::IMPORT_WAREHOUSE:
                                $importTotal += $qty;
                                break;
                            case self::REFUND:
                                $refund += $qty;
                                break;
                            case self::NOT_SHIPPED:
                                $not_shipped += $qty;
                                break;
                            case self::BROKEN:
                                $broken += $qty;
                                break;
                            default:
                                break;
                        }
                    }
                    $inventory = $importTotal > 0 ? $importTotal - $exportTotal + $refund - $broken : 0;
                    $data[$category][$product] = [
                        'product' => $product,
                        'category' => $category,
                        'broken' => $broken,
                        'refund' => $refund,
                        'inventory' => $inventory,
                        'import' => $importTotal,
                        'export' => $exportTotal,
                        'not_shipped' => $not_shipped,
                    ];
                }
            }
            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return $data;
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

            $range = "Data contact!A2:AX";

            $response = $service->spreadsheets_values->get($this->sheetID, $range);

            $values = $response->getValues();
            $data = self::group_by(12, $values);
            $values = $data[$partner];
            $phone = array_keys(self::group_by(4, $values));
            $C11 = array_keys(self::group_by(43, $values));
            $C8 = array_keys(self::group_by(39, $values));
            $C13 = array_keys(self::group_by(46, $values));
            $data = [];
            if (empty($values)) {
                throw new BadRequestHttpException("Dữ liệu trống!");
            }

            foreach ($values as $k => $value) {
                $statusC13 = ArrayHelper::getValue($value, 46);
                $transferC13 = self::convertNumber([ArrayHelper::getValue($value, 49)]);
                $data[$k] = [
                    'code' => ArrayHelper::getValue($value, 0),
                    'date_register' => ArrayHelper::getValue($value, 2),
                    'name' => ArrayHelper::getValue($value, 3),
                    'phone' => ArrayHelper::getValue($value, 4),
                    'status' => ArrayHelper::getValue($value, 39),
                    'revenue' => ArrayHelper::getValue($value, 40),
                    'status_shipping' => ArrayHelper::getValue($value, 43),
                    'status_C13' => $statusC13,
                    'transfer_C13' => $transferC13
                ];
            }
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return [
            'data' => $data,
            'filter' => [
                'phone' => $phone,
                'C8' => $C8,
                'C11' => $C11,
                'C13' => $C13
            ]
        ];
    }


    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    function actionGetSale($filter = null, $searchData = null)
    {

        $dataSet = $product = $source = $marketer = [];

        $partner = \Yii::$app->request->get('partner');
        $startDate = false;
        $endDate = false;
        $s_marketer = $s_product = $s_source = null;
        $sumRevenueC8 = $totalC3 = $totalC8 = $totalC11 = $total_C8_C3 = $total_C11_C3 = $total_C11_C8 = 0;

        try {
            if ($searchData && !empty($searchData)) {
                $data = (array)$searchData;
            } else {
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

            }
            ///Search overwrite
            if ($searchData && !empty($searchData)) {
                $s_marketer = isset($filter['marketer']) ? $filter['marketer'] : null;
                $s_source = isset($filter['source']) ? $filter['source'] : null;
                $s_product = isset($filter['product']) ? $filter['product'] : null;
                $s_date = !empty($filter['date']) ? explode(' - ', $filter['date']) : null;
                if ($s_date) {
                    $startDate = \DateTime::createFromFormat('d-m-Y', $s_date[0])->format('d-m-Y');
                    $endDate = \DateTime::createFromFormat('d-m-Y', $s_date[1])->format('d-m-Y');

                    $startDate = strtotime($startDate);
                    $endDate = strtotime($endDate);
                }
            }
            $labels = array_keys($data);
            //end
            foreach ($labels as $k => $label) {
                if ($startDate && $endDate) {
                    $atDate = strtotime(str_replace('/', '-', $label));
                    if (!($atDate >= $startDate && $atDate <= $endDate)) {
                        unset($labels[$k]);
                        continue;
                    }
                }
                foreach ($data[$label] as $column => $value) {
                    if (!empty($s_marketer)) {
                        if (!in_array($value[13], $s_marketer) || $s_marketer !== $value[13]) {
                            continue;
                        }
                    }
                    if (!empty($s_source)) {
                        if (!in_array($value[11], $s_source) || $s_source !== $value[11]) {
                            continue;
                        }
                    }
                    if (!empty($s_product)) {
                        if (!in_array($value[6], $s_product) || $s_product !== $value[6]) {
                            continue;
                        }
                    }

                    $statusC8 = Helper::toLower($value[39]);
                    $numC8 = (double)$value[40];
                    if ($statusC8 === self::STATUS_OK) {
                        $sumRevenueC8 += $numC8;
                    }
                }

                $C3 = ArrayHelper::getColumn($data[$label], 51);
                $C8 = ArrayHelper::getColumn($data[$label], 56);
                $C4 = ArrayHelper::getColumn($data[$label], 52);
                $C6 = ArrayHelper::getColumn($data[$label], 54);
                $C7 = ArrayHelper::getColumn($data[$label], 55);
                $C11 = ArrayHelper::getColumn($data[$label], 57);

                $sumbC3 = array_sum($C3);
                $sumbC8 = array_sum($C8);
                $sumbC6 = array_sum($C6);
                $sumbC7 = array_sum($C7);
                $sumbC4 = array_sum($C4);
                $sumbC11 = array_sum($C11);


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
            }
            $total_C8_C3 = $totalC8 > 0 ? round($totalC8 / $totalC3 * 100, 1) : 0;
            $total_C11_C3 = $totalC11 > 0 ? round($totalC11 / $totalC3 * 100, 1) : 0;
            $total_C11_C8 = $totalC11 > 0 ? round($totalC11 / $totalC8 * 100, 1) : 0;

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return [
            'base' => $data,
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
                'product' => $product,
                'source' => $source,
                'marketer' => $marketer,
                'page' => [],
                'date' => null
            ]
        ];
    }

    static function convertNumber($arr)
    {
        return array_sum(array_map(function ($item) {
            return str_replace(',', '.', $item);
        }, $arr));
    }

    function actionGetFinance($filter = null, $searchData = null)
    {
        $dataSet = [];
        $startDate = $endDate = null;
        $partner = \Yii::$app->request->get('partner');
        $totalC8 =
        $totalC13 =
        $totalC13Trans =
        $totalC11 = $C13_C11 = $totalSumC11 = $totalSumC13 =
        $dv_da_dx = $thu_ho_da_dx = $vch_da_dx = $tien_da_dx =
        $total_dv = $total_thu_ho = $total_vch = $total_tien = 0;
        $filterC8 = $filterC13 = $filterC11 = [];

        try {
            if ($searchData && !empty($searchData)) {
                $data = (array)$searchData;
            } else {
                $client = static::initClient();
                $service = new \Google_Service_Sheets($client);
                $range = "Data contact!A2:BG";
                $response = $service->spreadsheets_values->get($this->sheetID, $range);
                $values = $response->getValues();
                if (empty($values)) {
                    throw new BadRequestHttpException("Dữ liệu trống!");
                }
                $partners = self::group_by(12, $values);
                $partner = $partners[$partner];
                $data = self::group_by(2, $partner);


                $filterC8 = self::group_by(39, $partner);
                $filterC11 = self::group_by(43, $partner);
                $filterC13 = self::group_by(46, $partner);
            }

            $labels = array_keys($data);


            ///Search overwrite
            if ($searchData && !empty($searchData)) {
                $s_statusC8 = isset($filter['statusC8']) ? $filter['statusC8'] : null;
                $s_statusC11 = isset($filter['statusC11']) ? $filter['statusC11'] : null;
                $s_statusC13 = isset($filter['statusC13']) ? $filter['statusC13'] : null;
                $s_date = !empty($filter['date']) ? explode(' - ', $filter['date']) : null;
                if ($s_date) {
                    $startDate = \DateTime::createFromFormat('d-m-Y', $s_date[0])->format('d-m-Y');
                    $endDate = \DateTime::createFromFormat('d-m-Y', $s_date[1])->format('d-m-Y');

                    $startDate = strtotime($startDate);
                    $endDate = strtotime($endDate);
                }
            }


            foreach ($labels as $k => $label) {
                $col8 = ArrayHelper::getColumn($data[$label], 40);
                $col8 = self::convertNumber($col8);
                $dataSet['C8'][$k] = $col8;

                $C11 = ArrayHelper::getColumn($data[$label], 57);
                $sumC11 = array_sum($C11);
                $totalSumC11 = $totalSumC11 + $sumC11;

                $C13 = ArrayHelper::getColumn($data[$label], 58);
                $sumC13 = array_sum($C13);
                $totalSumC13 = $totalSumC13 + $sumC13;

                $totalC11K = 0;
                $totalC13K = 0;

                foreach ($data[$label] as $column => $value) {

                    if ($startDate && $endDate) {
                        $atDate = strtotime(str_replace('/', '-', $label));
                        if (!($atDate >= $startDate && $atDate <= $endDate)) {
                            unset($labels[$k]);
                            continue;
                        }
                    }
                    if (!empty($s_statusC8)) {
                        if (!in_array($value[39], $s_statusC8) || $s_statusC8 !== $value[39]) {
                            continue;
                        }
                    }
                    if (!empty($s_statusC11)) {
                        if (!in_array($value[43], $s_statusC11) || $s_statusC11 !== $value[43]) {
                            continue;
                        }
                    }
                    if (!empty($s_statusC13)) {
                        if (!in_array($value[46], $s_statusC13) || $s_statusC13 !== $value[46]) {
                            continue;
                        }
                    }

                    $statusC11 = Helper::toLower($value[43]);
                    $statusC13 = Helper::toLower($value[46]);
                    $statusC8 = Helper::toLower($value[39]);
                    $numC8 = (double)$value[40];
                    $numDV = self::convertNumber([$value[48]]);
                    $numCashPartner = self::convertNumber([$value[49]]);
                    $numThuHo = self::convertNumber([$value[45]]);
                    $numVCH = self::convertNumber([$value[44]]);
                    $numTien = self::convertNumber([$value[49]]);

                    $total_dv += $numDV;
                    $total_vch += $numVCH;
                    $total_tien += $numTien;
                    $total_thu_ho += $numThuHo;
                    $totalC13Trans += $numCashPartner;
                    if ($statusC8 === self::STATUS_OK) {
                        $totalC8 += $numC8;
                    }
                    if ($statusC11 === self::PAYED) {
                        $totalC11 += $numC8;
                        $totalC11K += $numC8;
                    }
                    if ($statusC13 === self::TRANSFERRED) {
                        $totalC13 += $numC8;
                        $totalC13K += $numC8;
                        $dv_da_dx += $numDV;
                        $thu_ho_da_dx += $numThuHo;
                        $vch_da_dx += $numVCH;
                        $tien_da_dx += $numTien;
                    }
                }
                $dataSet['C11'][$k] = $totalC11K;
                $dataSet['C13'][$k] = $totalC13K;
            }
            $C13_C11 = $totalSumC13 > 0 ? round($totalSumC13 / $totalSumC11 * 100, 2) : 0;

        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return [
            'base' => $data,
            'calculate' => [
                'totalC13Trans' => $totalC13Trans,
                'totalC8' => $totalC8,
                'C11_C8' => round($totalC11 / $totalC8 * 100, 2),
                'C13' => round($totalC13, 2),
                'C13_C11' => $C13_C11,
                'C11' => round($totalC11, 2),
                'total_dv' => round($total_dv, 2),
                'total_thu_ho' => round($total_thu_ho, 2),
                'total_vch' => round($total_vch, 2),
                'dv_da_dx' => round($dv_da_dx, 2),
                'thu_ho_da_dx' => round($thu_ho_da_dx, 2),
                'vch_da_dx' => round($vch_da_dx, 2),
                'tien_da_dx' => round($tien_da_dx, 2),
                'C13_chua_dx' => round($totalC11 - $totalC13, 2),
                'dv_chua_dx' => round($total_dv - $dv_da_dx, 2),
                'vch_chua_dx' => round($total_vch - $vch_da_dx, 2),
                'thu_ho_chua_dx' => round($total_thu_ho - $thu_ho_da_dx, 2),
                'tien_chua_dx' => round($total_tien - $tien_da_dx, 2)
            ],
            'filter' => [
                'statusC8' => array_keys($filterC8),
                'statusC13' => array_keys($filterC13),
                'statusC11' => array_keys($filterC11)
            ],
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
        $client->setHttpClient(new Client([
            'verify' => "D:\cacert.pem"
        ]));
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