<?php


namespace common\helper;
use kartik\money\MaskMoney;
use yii\helpers\Html;
use yii\helpers\Url;

class Component
{
    static function delete($urk)
    {

        return Html::a('<i class="fa fa-trash"></i> xóa'
            , $urk, [
                'class' => 'btn btn-sm bg-white',
                'data-confirm' => 'Bạn chắc sẽ xóa dữ liệu này?',
                'data-method' => 'post',
                'data-pjax' => 0
            ]);
    }

    static function update($url)
    {
        return Html::a('<i class="fa fa-edit"></i> sửa', $url, ['class' => 'btn btn-sm bg-white']);
    }

    static function view($url)
    {
        return Html::a('<i class="fa fa-eye"></i> xem', $url, ['class' => 'btn btn-sm bg-white']);
    }
    static function money($form,$model,$name){
        return $form->field($model, $name)->widget(MaskMoney::classname(), [
            'options' => [
                'placeholder' => 'Nhập số tiền...'
            ],
            'pluginOptions' => [
                'prefix' => 'đ ',
                'allowNegative' => false,
                'allowZero' => false,
                'allowEmpty' => true
            ]
        ]);
    }

    static function reset(){
        $url = Url::toRoute(\Yii::$app->controller->getRoute());
        if(\Yii::$app->request->get('phone')){
            $url = Url::toRoute([\Yii::$app->controller->getRoute(),'phone' => \Yii::$app->request->get('phone')]);
        }
        return Html::a('Làm mới', $url, ['class' => 'btn btn-outline-warning']);
    }
}