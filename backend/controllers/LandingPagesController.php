<?php

namespace backend\controllers;

use backend\models\ProductsModel;
use backend\models\UserModel;
use common\helper\Helper;
use common\models\User;
use Yii;
use backend\models\LandingPages;
use backend\models\LandingPagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LandingPagesController implements the CRUD actions for LandingPages model.
 */
class LandingPagesController extends BaseController
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
     * Lists all LandingPages models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new LandingPagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if(Helper::userRole(UserModel::_MARKETING)){
                $dataProvider = $searchModel->search(array_merge(
                    Yii::$app->request->queryParams,
                    [
                       'LandingPagesSearch' => [
                           'user_id' => Yii::$app->user->getId()
                       ]
                    ]
                ));
            }
        $model = new LandingPages;
        if($id){
            $model = $this->findModel($id);
        }
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            if($model->save()){
                self::success("Tạo sản phẩm thành công!");
            }else{
                self::error(Helper::firstError($model));
            }
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /**
     * Displays a single LandingPages model.
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
     * Creates a new LandingPages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LandingPages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LandingPages model.
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
     * Deletes an existing LandingPages model.
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
     * Finds the LandingPages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LandingPages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LandingPages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
