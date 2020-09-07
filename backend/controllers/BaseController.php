<?php


namespace backend\controllers;


use backend\models\UserModel;
use common\helper\Helper;
use yii\helpers\Url;
use yii\web\Controller;

class BaseController extends Controller
{
    public function unLogin(){
        return [];
    }
    public function inLogin(){
        return [];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function init()
    {
        parent::init();

    }

    static function success($msg){
        return \Yii::$app->session->setFlash("success",$msg);
    }

    static function error($msg){
        return \Yii::$app->session->setFlash("error",$msg);
    }
}