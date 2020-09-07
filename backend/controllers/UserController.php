<?php

namespace backend\controllers;

use backend\models\ChangePass;
use common\helper\Helper;
use Yii;
use backend\models\UserModel;
use backend\models\UserSearchModel;
use yii\web\NotFoundHttpException;


class UserController extends BaseController
{

    public function actionIndex($id = null)
    {
        $searchModel = new UserSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['is_partner' => false]);
        $model = new UserModel;
        if($id){
            $model = $this->findModel($id);
        }
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){

            if($model->save()){
                self::success("Tạo tài khoản thành công!");
            }else{
                self::error(Helper::firstError($model));
            }
            return $this->redirect(['index']);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
    public function actionChangePassword(){
        $model = new ChangePass;
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            if($model->change()){
                self::success("Đổi mật khẩu thành công!");
            }else{
                self::error(Helper::firstError($model));
            }
            return $this->goBack();
        }
    }
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                self::success("Đổi mật khẩu thành công!");
            }
            return $this->refresh();
        }
        unset($model->password_hash);
        $changePassword = new ChangePass;

        return $this->render('view', [
            'model' => $model,
            'changePass' => $changePassword
        ]);
    }

    /**
     * Creates a new UserModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserModel model.
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
     * Deletes an existing UserModel model.
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
     * Finds the UserModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
