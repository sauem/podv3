<?php

namespace backend\controllers;

use backend\jobs\doScanContactByCountry;
use backend\models\ContactsAssignment;
use backend\models\ContactsLog;
use backend\models\ContactsLogImport;
use backend\models\OrdersModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\User;
use Yii;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * ContactsController implements the CRUD actions for ContactsModel model.
 */
class ContactsController extends BaseController
{
    public function actionIndex($lastTime = null)
    {

        doScanContactByCountry::apply();

        $phone = Yii::$app->request->get("phone");
        if ((Helper::userRole(UserModel::_ADMIN) || Helper::userRole(UserModel::_MARKETING)) && !$phone) {
            $this->redirect(Url::toRoute(['/contacts-assignment/index']));
        }
        $saleID = Yii::$app->user->getId();
        $user = UserModel::findOne($saleID);
        $phone = isset($user->processing) ? $user->processing->contact_phone : ContactsAssignment::prevAssignment();
        $pendingPhone = isset($user->pending) ? $user->pending->contact_phone : null;

        // Lần gọi 1
        $searchModel = new ContactsSearchModel();
        $dataProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => [
                        ContactsModel::_NEW,
                        ContactsModel::_PENDING,
                        ContactsModel::_CALLBACK,
                    ]
                ]
            ]
        ));
        $failureProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => [
                        ContactsModel::_CANCEL,
                        ContactsModel::_SKIP,
                        ContactsModel::_DUPLICATE,
                        ContactsModel::_NUMBER_FAIL
                    ]
                ]
            ]
        ));
        $successProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => ContactsModel::_OK
                ]
            ]
        ));
        $callbackTime = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => [
                        ContactsModel::_PENDING,
                        ContactsModel::_CALLBACK,
                    ]
                ]
            ]
        ));

        // Lần gọi 2
        $_dataProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $pendingPhone,
                    'status' => [
                        ContactsModel::_NEW,
                        ContactsModel::_PENDING,
                        ContactsModel::_CALLBACK,
                    ]
                ]
            ]
        ), false, true);

        $_failureProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $pendingPhone,
                    'status' => [
                        ContactsModel::_CANCEL,
                        ContactsModel::_SKIP,
                        ContactsModel::_DUPLICATE,
                        ContactsModel::_NUMBER_FAIL
                    ]
                ]
            ]
        ), false, true);
        $_successProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $pendingPhone,
                    'status' => ContactsModel::_OK
                ]
            ]
        ), false, true);
        $_callbackTime = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $pendingPhone,
                    'status' => [
                        ContactsModel::_PENDING,
                        ContactsModel::_CALLBACK,
                    ]
                ]
            ]
        ), false, true);


        $modelNote = new ContactsLog;
        $info = ContactsModel::find()->where(['phone' => $phone])
            ->orderBy(['created_at' => SORT_ASC])
            ->with('saleAssign')
            ->one();

        $_info = ContactsModel::find()->where(['phone' => $pendingPhone])
            ->orderBy(['created_at' => SORT_ASC])
            ->with('saleAssign')
            ->one();

        $user = UserModel::findOne(Yii::$app->user->getId());
        $order = new OrdersModel;

        // Lịch sử đơn hàng
        $histories = new ActiveDataProvider([
            'query' => OrdersModel::find()->where(['user_id' => Yii::$app->user->getId()]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        //Lịch sử cuộc gọi
        $contactHistories = new ActiveDataProvider([
            'query' => ContactsLog::find()
                ->rightJoin('contacts',
                    '(contacts.id=contacts_log.contact_id OR contacts.code=contacts_log.contact_code)')
                ->andWhere(['contacts_log.user_id' => Yii::$app->user->getId(),])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        // Helper::prinf($contactHistories->query->one()->getContact()->createCommand()->rawSql);
        // Lịch sử cuộc gọi hiện tại
        $currentHistories = new ActiveDataProvider([
            'query' => ContactsLog::find()
                ->rightJoin('contacts', 'contacts.id=contacts_log.contact_id')
                ->andWhere(['contacts_log.user_id' => Yii::$app->user->getId(),])
                ->andWhere(['contacts.phone' => $phone])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        //Helper::prinf($currentHistories->query->createCommand()->rawSql);
        // Lịch sử cuộc gọi lần gọi 2
        $_currentHistories = new ActiveDataProvider([
            'query' => ContactsLog::find()
                ->rightJoin('contacts', 'contacts.id=contacts_log.contact_id')
                ->andWhere(['contacts_log.user_id' => Yii::$app->user->getId(),])
                ->andWhere(['contacts.phone' => $pendingPhone])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $phonesAssign = ContactsAssignment::findAll(['user_id' => Yii::$app->user->getId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'failureProvider' => $failureProvider,
            'successProvider' => $successProvider,
            'callbackProvider' => $callbackTime,
            '_dataProvider' => $_dataProvider,
            '_failureProvider' => $_failureProvider,
            '_successProvider' => $_successProvider,
            '_callbackProvider' => $_callbackTime,
            'modelNote' => $modelNote,
            'order' => $order,
            'user' => $user,
            'info' => $info,
            '_info' => $_info,
            'histories' => $histories,
            'phonesAssign' => $phonesAssign,
            'contactHistories' => $contactHistories,
            'currentHistories' => $currentHistories,
            '_currentHistories' => $_currentHistories
        ]);
    }


    /**
     * Displays a single ContactsModel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = "empty";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContactsModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContactsModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContactsModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->layout = "empty";

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionApprovePending($id)
    {
        $this->layout = "empty";
        $model = ContactsLogImport::findOne($id);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $contact = new ContactsModel;
                if ($contact->load(Yii::$app->request->post(), "ContactsLogImport")) {
                    if ($contact->save()) {
                        $model->delete();
                        Helper::showMessage("Áp dụng thành công liên hệ chính!");
                    } else {
                        Helper::showMessage(Helper::firstError($contact));
                    }
                }
                return $this->redirect(Url::toRoute(['/contacts-assignment/pending']));
            }
            Helper::showMessage(Helper::firstError($model));
            return $this->redirect(Url::toRoute(['/contacts-assignment/pending']));
        }
        return $this->render("modal\approve_pending", [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing ContactsModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ContactsModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContactsModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContactsModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    function actionHistories()
    {
        return $this->render("_histories");
    }
}
