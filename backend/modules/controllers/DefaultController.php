<?php

namespace backend\modules\controllers;

use backend\models\ContactsModel;
use MongoDB\Driver\Exception\AuthenticationException;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;


/**
 * Default controller for the `api` module
 */
class DefaultController extends ActiveController
{
    public $modelClass = ContactsModel::class;
    /**
     * Renders the index view for the module
     * @return string
     *
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
//        $token = \Yii::$app->request->post("token");
//        if(!$token){
//            throw new NotFoundHttpException("Access!");
//        }
    }

}
