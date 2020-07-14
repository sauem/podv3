<?php

namespace backend\controllers;

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

    public function actionTest()
    {
        $phones = ContactsModel::find()->addSelect(['phone'])->distinct()->asArray()->all();
        $phones = ArrayHelper::getColumn($phones, 'phone');
        $users = AuthAssignment::find()->with('user')->where(['item_name' => UserModel::_SALE])->asArray()->all();
        $users = ArrayHelper::getColumn($users, 'user_id');

        foreach ($users as $user) {
            $count = self::countAssignUser($user);
            if ($count >= 2) {
                self::pendingStatus($user);
                continue;
            } else {
                foreach ($phones as $k => $phone) {
                    $exitStatus = self::getStatusUser($user, $phone);
                    if ($exitStatus) {
                        self::changeStatusPending($exitStatus);
                        continue;
                    } else {
                        if (!self::phoneExitsts($phone) && self::countAssignUser($user) < 2) {
                            $count = self::countAssignUser($user);
                            switch ($count) {
                                case 1:
                                    self::assignUser($phone, $user, ContactsAssignment::_PENDING);
                                    break;
                                default:
                                    self::assignUser($phone, $user, ContactsAssignment::_PROCESSING);
                                    break;
                            }
                        } else {
                            continue;
                        }
                    }
                }
            }
        }
    }

    static function changeStatusPending(ContactsAssignment $exitStatus)
    {
        if ($exitStatus) {
            $exitStatus->status = ContactsAssignment::_PROCESSING;
            $exitStatus->save();
        }
    }

    static function phoneExitsts($phone)
    {
        $exitsts = ContactsAssignment::find()->where(['contact_phone' => $phone])->count();
        if ($exitsts > 0) {
            return true;
        }
        return false;
    }

    static function assignUser($phone, $user, $status = ContactsAssignment::_PROCESSING)
    {
        $model = new ContactsAssignment;
        $model->load([
            'user_id' => $user,
            'contact_phone' => $phone,
            'status' => $status
        ], '');
        $model->save();
    }

    static function countAssignUser($user)
    {
        $count = ContactsAssignment::find()
            ->where(['status' => [ContactsAssignment::_PENDING, ContactsAssignment::_PROCESSING]])
            ->andWhere(['user_id' => $user])->count();
        return $count;
    }

    static function getStatusUser($user, $phone)
    {
        $assign = ContactsAssignment::findOne(['user_id' => $user, 'contact_phone' => $phone]);
        return $assign;
    }

    static function pendingStatus($user)
    {
        $assign = ContactsAssignment::find()
            ->where(['user_id' => $user, 'status' => ContactsAssignment::_PENDING])
            ->orderBy(['created_at' => SORT_ASC]);
        if ($assign->count() > 1) {
            self::changeStatusPending($assign->all()[0]);
        }
    }

    function actionSumContact(){
        $searchModel = new ContactsSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->groupBy(['phone'])->with('assignment')->with('sumContact');

        Helper::prinf($dataProvider->query->all());
    }

    function actionCountry(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $country = Yii::$app->params['country'];
        return $country;
    }
}
