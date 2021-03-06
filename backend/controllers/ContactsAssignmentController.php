<?php

namespace backend\controllers;

use backend\models\ContactsLog;
use backend\models\ContactsLogImport;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\FormInfo;
use backend\models\LandingPages;
use backend\models\LogsImport;
use backend\models\UploadForm;
use backend\models\UserModel;
use common\helper\Component;
use common\helper\Helper;
use Yii;
use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactsAssignmentController implements the CRUD actions for ContactsAssignment model.
 */
class ContactsAssignmentController extends BaseController
{

    public function actionIndex()
    {

        $searchModel = new ContactsSearchModel();
        $completeProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'status' => [
                        ContactsModel::_OK,
                    ]
                ]
            ]
        ));

        $callProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'status' => [
                        ContactsModel::_PENDING,
                    ]
                ]
            ]
        ));
        $pendingProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'status' => [
                        ContactsModel::_NEW,
                        //ContactsModel::_CANCEL,
                        //ContactsModel::_DUPLICATE,
                        //ContactsModel::_NUMBER_FAIL,
                        //ContactsModel::_SKIP,
                        ContactsModel::_PENDING,
                        ContactsModel::_CALLBACK
                    ]
                ]
            ]
        ));

        $allProvider = new ActiveDataProvider([
            'query' => ContactsModel::find(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'completeProvider' => $completeProvider,
            'callbackProvider' => $callProvider,
            'pendingProvider' => $pendingProvider,
            'allProvider' => $allProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single ContactsAssignment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($phone)
    {
        $model = ContactsModel::findOne(['phone' => $phone]);
        if (!$model) {
            throw new NotFoundHttpException("Không tồn tại số điện thoại này!");
        }
        $info = ContactsModel::find()->where(['phone' => $phone])
            ->orderBy(['created_at' => SORT_ASC])
            ->with('assignment')
            ->one();

        $searchModel = new ContactsSearchModel();
        $dataProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => [
                        ContactsModel::_NEW,
                    ]
                ]
            ]
        ), false);

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
        ), false);

        $successProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => ContactsModel::_OK
                ]
            ]
        ), false);
        $failureProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => [
                        ContactsModel::_CANCEL,
                        ContactsModel::_NUMBER_FAIL,
                        ContactsModel::_DUPLICATE,
                        ContactsModel::_SKIP
                    ],
                ]
            ]
        ), false);

        $histories = new ActiveDataProvider([
            'query' => ContactsLog::find()
                ->joinWith('contact')
                ->where(['=', 'contacts.phone', $phone])
                ->orderBy(['created_at' => SORT_DESC])
            ,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $assigment = ContactsAssignment::findOne(['contact_phone' => $phone]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'callbackProvider' => $callbackTime,
            'successProvider' => $successProvider,
            'failureProvider' => $failureProvider,
            'info' => $info,
            'histories' => $histories,
            'assignment' => $assigment,
        ]);
    }

    /**
     * Creates a new ContactsAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContactsAssignment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContactsAssignment model.
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
     * Deletes an existing ContactsAssignment model.
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
     * Finds the ContactsAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContactsAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContactsAssignment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    function actionImport()
    {
        $this->layout = "empty";
        $model = new UploadForm;
        return $this->render("modal/_contact_import", [
            'model' => $model
        ]);
    }

    function actionPending()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ContactsLogImport::find(),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $model = new LandingPages;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                self::updateContactPending($model->link);
                self::success("Tạo trang đích thành công!");
            } else {
                self::error("Tạo trang đích thành công!");
            }
            return $this->redirect(Url::toRoute(['pending']));
        }
        return $this->render("pending", [
            'dataProvider' => $dataProvider
        ]);
    }

    static function updateContactPending($link)
    {
        $importLogs = ContactsLogImport::findAll(['link' => $link]);

        if ($importLogs) {

            foreach ($importLogs as $k => $log) {
                $contact = new ContactsModel;
                $log->link = $link;
                $data = ArrayHelper::toArray($log);
                unset($data["id"]);
                $log->delete();
                if (!$contact->load($data, '') || $contact->save()) {
                    Helper::showMessage(Helper::firstError($contact));
                }
            }

            Helper::showMessage("Có " . sizeof($importLogs) . " liên hệ chờ   được duyệt!");
        }
    }

    public function actionSaveOption()
    {
        $model = new FormInfo;
        $post = Yii::$app->request->post();
        $oldContent = ArrayHelper::getValue($post, "old_content");
        if ($oldContent == null) {
            $oldContent = "";
        }
        if (Yii::$app->request->isPost && $model->load($post)) {
            if ($model->save()) {
                // Find pending contact with old option
                $contactsLog = ContactsLogImport::findAll(['option' => $oldContent]);

                if ($contactsLog) {
                    foreach ($contactsLog as $log) {
                        $contact = new ContactsModel;
                        $data = ArrayHelper::toArray($log);
                        unset($data['id']);
                        //set new option after update
                        $data['option'] = $model->content;
                        $log->delete();

                        if (!$contact->load($data, '') || !$contact->save()) {
                            self::error(Helper::firstError($contact));
                        }
                    }
                    self::success("Cập nhật liên hệ với yêu cầu {$model->content} thành công!");
                }
            } else {
                self::error(Helper::firstError($model));
            }
            return $this->redirect(Url::toRoute(['pending']));
        }
    }
}
