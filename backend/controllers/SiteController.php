<?php

namespace backend\controllers;

use backend\jobs\autoBackup;
use backend\jobs\doScanContactByCountry;
use backend\models\Backups;
use backend\models\ContactsAssignment;
use backend\models\ContactsLog;
use backend\models\ContactsModel;
use backend\models\OrdersContacts;
use backend\models\OrdersModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\Common;
use Yii;
use yii\data\ActiveDataProvider;
use common\models\LoginForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
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
        if (Helper::userRole(UserModel::_PARTNER)) {
            return $this->redirect('/client/index');
        }

        $totalContact = ContactsModel::find()->count();
        $totalOrder = OrdersModel::find()->count();

        $conversionRate = 0;
        if ($totalContact > 0 && $totalOrder > 0) {
            $conversionRate = $totalOrder / $totalContact * 100;
        }
        $totalAmount = OrdersContacts::find()->count('id');
        return $this->render('index', [
            'totalContact' => $totalContact,
            'totalOrder' => $totalOrder,
            'totalAmount' => $totalAmount,
            'conversionRate' => $conversionRate
        ]);
    }

    public function actionReport()
    {
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

            doScanContactByCountry::apply();

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

    function actionDeleteBackup($id)
    {
        $md = Backups::findOne($id);
        if ($md) {
            autoBackup::dropDriver($md->name);
            $md->delete();
            return $this->redirect(['site/web-settings']);
        }
        return $this->redirect(['site/web-settings']);
    }

    function actionBranking($start = null, $end = null)
    {
        $query = ContactsLog::find()
            ->joinWith('user')
            ->select([
                'contacts_log.status',
                'contacts_log.contact_code',
                'contacts_log.user_id',
                'contacts_log.created_at',
                'user.username as sale',
                'contacts_log.user_id',
                'SUM( IF (contacts_log.status = "ok", 1,0)) as ok',
                'SUM( IF (contacts_log.status = "pending", 1,0)) as pending',
                'SUM( IF (contacts_log.status = "cancel", 1,0)) as cancel',
                'SUM( IF ( contacts_log.status = "number_fail" || contacts_log.status = "duplicate" || contacts_log.status = "skip" , 1,0)) as failed',
                // Đếm nếu status = "callback" và contact code đó chỉ xuất hiện 1 lần
                 'SUM( CASE WHEN contacts_log.status = "callback"  THEN 1 else 0 END) as callback',
                ])
            ->groupBy(['contacts_log.user_id']);
        if($start && $end){
            $query->filterWhere(['between','contacts_log.created_at', $start, $end]);
        }else{
            $beginOfDay = strtotime("midnight", time());
            $endOfDay = strtotime("tomorrow", $beginOfDay);

            $query->filterWhere(['between','contacts_log.created_at', $beginOfDay, $endOfDay]);
        }
        $query = $query->asArray()->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query
        ]);
        return $this->render("branking", [
            'dataProvider' => $dataProvider
        ]);
    }
}
