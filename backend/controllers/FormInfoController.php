<?php

namespace backend\controllers;

use backend\models\ContactsModel;
use backend\models\UploadForm;
use common\helper\Helper;
use Yii;
use backend\models\FormInfo;
use backend\models\FormInfoSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormInfoController implements the CRUD actions for FormInfo model.
 */
class FormInfoController extends BaseController
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
     * Lists all FormInfo models.
     * @return mixed
     */
    function actionImport()
    {
        $this->layout = "empty";
        $model = new UploadForm;
        return $this->render("import", ['model' => $model]);

    }

    public function actionIndex($id = "")
    {
        $searchModel = new FormInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new FormInfo;

        if ($id) {
            $model = FormInfo::findOne($id);
        }
        if (Yii::$app->request->get('content')) {
            $content = Yii::$app->request->get("content");
            $model->load(['content' => $content], "");
        }

        $optionProvider = new ActiveDataProvider([
            'query' => ContactsModel::find()
                ->with('page')
                ->with('formInfo')
                ->where(['<>', 'option', ''])
                ->groupBy('option'),
            'pagination' => [
                'pageSize' => 10
            ]

        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'optionProvider' => $optionProvider
        ]);
    }

    /**
     * Displays a single FormInfo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FormInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FormInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FormInfo model.
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
     * Deletes an existing FormInfo model.
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
     * Finds the FormInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FormInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FormInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionRemote($option, $category_id)
    {
        $this->layout = "empty";

        $model = new FormInfo;
        $model->content = $option;
        $model->category_id = $category_id;

        return $this->render("remote", [
            'model' => $model
        ]);
    }
}
