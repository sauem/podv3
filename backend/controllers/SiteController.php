<?php

namespace backend\controllers;

use backend\jobs\doScanContact;
use backend\jobs\scanNewContact;
use backend\models\AuthAssignment;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = "empty";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionData()
    {
        $user_id = 25;
        $user = ContactsAssignment::find()->where(['user_id' => $user_id,"status" => ContactsAssignment::_PENDING])
            ->andWhere(['!=','callback_time', ""])
            ->orderBy(["callback_time" => SORT_ASC])
            ->one();
        Helper::prinf($user);
    }

    function actionCountry()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $country = Yii::$app->params['country'];
        return $country;
    }
}
