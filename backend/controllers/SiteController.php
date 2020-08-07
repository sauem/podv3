<?php

namespace backend\controllers;

use backend\jobs\autoBackup;
use backend\jobs\doScanContact;
use backend\jobs\scanNewContact;
use backend\models\AuthAssignment;
use backend\models\Backups;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\OrdersContacts;
use backend\models\OrdersItems;
use backend\models\OrdersModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\Common;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
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
            'web-settings' => [
                'class' => \yii2mod\settings\actions\SettingsAction::class,
                'viewParams' => [
                    'dataProvider' => new ActiveDataProvider([
                        'query' => Backups::find()
                    ])
                ],
                // also you can use events as follows:
                'on beforeSave' => function ($event) {
                    // your custom code
                    // your custom code
                    foreach ($event->form->attributes as $key => $attribute) {
                        if (empty($attribute)) {
                            Yii::$app->settings->remove("Common", $key);
                        }
                    }
                },
                'on afterSave' => function ($event) {

                },
                'modelClass' => Common::class,
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
        $totalContact = ContactsModel::find()->count();
        $totalOrder = OrdersModel::find()->count();

        $conversionRate = 0;
        if($totalContact > 0 && $totalOrder > 0){
            $conversionRate = $totalOrder/ $totalContact * 100;
        }
        $totalAmount = OrdersContacts::find()->count('id');
        return $this->render('index',[
            'totalContact'  => $totalContact,
            'totalOrder' => $totalOrder,
            'totalAmount' => $totalAmount,
            'conversionRate' => $conversionRate
        ]);
    }
    public function actionReport(){
        $sale = UserModel::listSales();
        return $this->render("report");
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = "login";
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
        $beginOfDay = strtotime("midnight", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
        $count = ContactsAssignment::find()->where(['user_id' => 25])
            ->orderBy(['created_at' => SORT_ASC])
            ->andFilterWhere([
                'between', 'created_at', $beginOfDay, $endOfDay
            ])->count();
       $user = UserModel::findOne(25)->getAttribute("phone_of_day");

        echo $user == $count;
    }

    function actionCountry()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $country = Yii::$app->params['country'];
        return $country;
    }
    function actionDeleteBackup($id){
        $md = Backups::findOne($id);
        if($md){
            $md->delete();
            return $this->redirect(['site/web-settings']);
        }
        return  $this->redirect(['site/web-settings']);
    }
}
