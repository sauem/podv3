<?php


namespace backend\controllers;


use backend\models\OrdersModel;
use common\helper\Helper;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Response;
use yii2tech\spreadsheet\Spreadsheet;

class ExportController extends BaseController
{
    function actionOrder(){
       \Yii::$app->response->format = Response::FORMAT_JSON;
        $orderId = \Yii::$app->request->post('orderID');
        $order =  OrdersModel::findOne($orderId);
        if(!$order){
            return [
                'success' => 0,
                'msg' => 'Đơn hàng không tồn tại'
            ];
        }
        $export =  new Spreadsheet([
           'title' => 'Đơn hàng',
        ]);
        $export->dataProvider  = new ArrayDataProvider([
            'allModels' =>  null
        ]);

        //Info khách hàng
        $export->renderCell("A1","Khách hàng");
        $export->mergeCells("A1:B1");
        $export->applyCellStyle("A1:B1",[
            'font' => [
                'size' => 12,
                'bold' => true
            ],
            'fill' => ['argb' => 'F3E2A9'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $export->renderCell("A2","Tên");
        $export->renderCell("A3","Số điện thoại");
        $export->renderCell("A4","Email");
        $export->renderCell("A5","Địa chỉ");
        $export->renderCell("A6","Quận huyện");
        $export->renderCell("A7","Tỉnh/Thành phố");
        $export->renderCell("A8","Mã bưu điện");
        $export->renderCell("A9","Quốc gia");
        $export->renderCell("A10","Hình thức thanh toán");
        $export->renderCell("A11","Hóa đơn chuyển khoản");

        $export->renderCell("B2",$order->customer_name);
        $export->renderCell("B3",$order->customer_phone);
        $export->renderCell("B4",$order->customer_email);
        $export->renderCell("B5",$order->address);
        $export->renderCell("B6",$order->district);
        $export->renderCell("B7",$order->city);
        $export->renderCell("B8",$order->zipcode);
        $export->renderCell("B9",Helper::getCountry($order->country));
        $export->renderCell("B10", $order->payment ? $order->payment->name : "Không thiết lập");

        $colB = 10;
        if($order->billings){
            foreach ($order->billings as $k => $billing){
                $url = Url::toRoute("/file/$billing->path",'http');
                $url = "=HYPERLINK(\"$url\")";
                $colB = $colB + $k;
                $export->renderCell("B$colB" , $url);
            }
            $endCol = $colB+sizeof($order->billings);
            $export->mergeCells("A$colB:A$endCol");
        }
        $export->applyCellStyle("B1:B12",[
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]);
        $endCol = isset($endCol) ? $endCol : $colB + 2;
        $export->applyCellStyle("A1:B$endCol",[
           'borders' => [
               'outline' => [
                   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                   'color' => ['argb' => '424242'],
               ]
           ]
        ]);
        foreach (range("A","B") as $col){
            $export->getDocument()->getActiveSheet()->getColumnDimension($col)->setWidth(25);
        }

        $export->renderCell("E1","Sản phẩm đặt mua");
        $export->applyCellStyle("E1:G1",[
            'font' => [
                'size' => 12,
                'bold' => true
            ],
            'fill' => ['argb' => 'F3E2A9'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $export->mergeCells("E1:G1");
        $export->renderCell("E2","Sản phẩm");
        $export->renderCell("F2","Lựa chọn");
        $export->renderCell("G2","Tổng");
        if($order->items){
            $startE = 3;
            $endE = sizeof($order->items) + $startE;
            foreach ($order->items as $k => $item){
                $export->renderCell("E$startE",$item->product_sku . "|" . $item->product->name);
                $export->renderCell("F$startE",$item->product_option);
                $export->renderCell("G$startE",Helper::formatExcel($item->price));
            }
            $export->renderCell("E$endE","Phí vận chuyển");
            $export->renderCell("G$endE", Helper::formatExcel($order->shipping_price));
            $export->renderCell("E".($endE+1),"Tổng hóa đơn");
            $export->renderCell("G" . ($endE+1), Helper::formatExcel($order->total));

        }
        $export->applyCellStyle("E1:G".($endE+2),[
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '424242'],
                ]
            ]
        ]);
        foreach (range("E","G") as $col){
            $export->getDocument()->getActiveSheet()->getColumnDimension($col)->setWidth(25);
        }

        if($order->contacts){
            $export = self::renderContacts($export , $order);
        }
        $fileName = $order->id. "_".time().".xlsx";
        $export->save(\Yii::getAlias("@files")  . "/$fileName");
        return  [
            'success' => 1,
            'url' =>  $url = Url::to("/file/$fileName")
        ];
    }

    static function renderContacts(Spreadsheet $export , $order){
        $export->renderCell("I1","Thông tin đăng kí");
        $export->mergeCells("I1:L1");
        $export->renderCell("I2","Đăng kí");
        $export->renderCell("J2","Lựa chọn");
        $export->renderCell("K2","Trang đích");
        $export->renderCell("L2","Ngày đăng kí");

        $export->applyCellStyle("I1:L1",[
            'font' => [
                'size' => 12,
                'bold' => true
            ],
            'fill' => ['argb' => 'F3E2A9'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $startI = 3;
        $endI = sizeof($order->contacts) + $startI;
        foreach ($order->contacts as $k => $contact){
            $export->renderCell("I$startI",$contact->contact->name);
            $export->renderCell("J$startI", $contact->contact->option);
            $export->renderCell("K$startI", $contact->contact->link);
            $export->renderCell("L$startI", Helper::toDate($contact->contact->created_at,'d/m/Y H:i:s'));
        }
        $export->applyCellStyle("I1:L".($endI+2),[
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '424242'],
                ]
            ]
        ]);

        foreach (range("I","L") as $col){
            $export->getDocument()->getActiveSheet()->getColumnDimension($col)->setWidth(30);
        }
        return $export;
    }
}