<?php


namespace common\helper;
use kartik\money\MaskMoney;
use yii\helpers\Html;
use yii\helpers\Url;

class Component
{
    static function delete($urk)
    {

        return Html::a('<i class="fe-trash"></i> xóa'
            , $urk, [
                'class' => 'btn btn-sm m-1 btn-warning',
                'data-confirm' => 'Bạn chắc sẽ xóa dữ liệu này?',
                'data-method' => 'post',
                'data-pjax' => 0
            ]);
    }

    static function update($url, $remote = false, $modal = null)
    {
        if($remote){
            return Html::button('<i class="fe-edit"></i> sửa', [
                'data-remote' => $url,
                'data-toggle' => 'modal',
                'data-target' => $modal,
                'data-pjax' => '0',
                'class' => 'btn m-1 btn-sm bg-white'

            ]);
        }
        return Html::a('<i class="fe-edit"></i> sửa', $url, ['data-pjax' => '0','class' => 'btn m-1 btn-sm bg-white']);
    }

    static function view($url)
    {
        return Html::a('<i class="fe-eye"></i> xem', $url, ['data-pjax' => '0' ,'class' => 'btn m-1 btn-sm bg-white']);
    }
    static function money($form,$model,$name){
        return $form->field($model, $name)->widget(MaskMoney::classname(), [
            'options' => [
                'placeholder' => 'Nhập số tiền...'
            ],
            'pluginOptions' => [
                'prefix' => '',
                'allowNegative' => false,
                'allowZero' => false,
                'allowEmpty' => true
            ]
        ]);
    }

    static function reset($name = "Làm mới"){
        $url = Url::toRoute(\Yii::$app->controller->getRoute());
        if(\Yii::$app->request->get('phone')){
            $url = Url::toRoute([\Yii::$app->controller->getRoute(),'phone' => \Yii::$app->request->get('phone')]);
        }
        return Html::a("<i class='fe-refresh-ccw'></i> $name", $url, ['class' => 'btn btn-sm btn-outline-warning']);
    }

    static function renderLogs(){
        $path = \Yii::getAlias("@backend/web/file/logs.txt");
        $file = fopen($path, "r");
        $content = "";
        if(filesize($path) > 0){
            $content  = fread($file, filesize($path));
            fclose($file);
        }
        return $content;
    }
}