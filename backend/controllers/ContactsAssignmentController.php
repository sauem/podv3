<?php

namespace backend\controllers;

use backend\models\ContactsLog;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\UploadForm;
use common\helper\Component;
use common\helper\Helper;
use Yii;
use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactsAssignmentController implements the CRUD actions for ContactsAssignment model.
 */
class ContactsAssignmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ContactsAssignment models.
     * @return mixed
     */
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
                        ContactsModel::_CALLBACK
                    ]
                ]
            ]
        ));
        $pendingProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'status' => [
                        ContactsModel::_NEW
                    ]
                ]
            ]
        ));
        return $this->render('index', [
            'searchModel' => $searchModel,
            'completeProvider' => $completeProvider,
            'callbackProvider' => $callProvider,
            'pendingProvider' => $pendingProvider
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
        ),false);

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
        ),false);

        $successProvider = $searchModel->search(array_merge(
            Yii::$app->request->queryParams,
            [
                'ContactsSearchModel' => [
                    'phone' => $phone,
                    'status' => ContactsModel::_OK
                ]
            ]
        ),false);

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

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'callbackProvider' => $callbackTime,
            'successProvider' => $successProvider,
            'info' => $info,
            'histories' => $histories
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

    function actionImport(){
        $this->layout = "empty";
        $model = new UploadForm;
       return $this->render("_import_modal",[
           'model' => $model
       ]);
    }
}
