<?php

namespace backend\controllers;

use backend\jobs\doScanContact;
use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use backend\models\ContactsLog;
use backend\models\LogsImport;
use backend\models\OrdersModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\User;
use Yii;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\Url;
use yii\rbac\Assignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ContactsController implements the CRUD actions for ContactsModel model.
 */
class ContactsController extends BaseController
{
    public function actionIndex()
    {
        $phone = Yii::$app->request->get("phone");
        if (Helper::userRole(UserModel::_ADMIN) && !$phone) {
            $this->redirect(Url::toRoute(['/contacts-assignment/index']));
        }
        if (Helper::userRole(UserModel::_SALE)) {
            $saleID = Yii::$app->user->getId();
            $phone = UserModel::findOne($saleID);
            //$phone = isset($phone->processing) ? $phone->processing->contact_phone : ContactsAssignment::prevAssignment();
        }

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

        $modelNote = new ContactsLog;
        $info = ContactsModel::find()->where(['phone' => $phone])
            ->orderBy(['created_at' => SORT_ASC])
            ->with('saleAssign')
            ->one();
        $user = UserModel::findOne(Yii::$app->user->getId());
        $order = new OrdersModel;

        $histories =  new ActiveDataProvider([
            'query' => OrdersModel::find()->where(['user_id' => Yii::$app->user->getId()]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

      //  Helper::prinf($failureProvider->query->createCommand()->getRawSql());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'failureProvider' => $failureProvider,
            'successProvider' => $successProvider,
            'callbackProvider' => $callbackTime,
            'modelNote' => $modelNote,
            'order' => $order,
            'user' => $user,
            'info' => $info,
            'histories' => $histories
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
