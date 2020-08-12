<?php


namespace backend\controllers;


use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\ManagerSearch;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ContactManagerController extends BaseController
{
    function actionIndex()
    {
        $searchModel = new ContactsAssignmentSearch();
        $assignProvider = $searchModel->search(array_merge(\Yii::$app->request->queryParams, [
            'ContactsAssignmentSearch' => [
                'user_id' => \Yii::$app->user->getId()
            ]
        ]));

        return $this->render("index", [
            'assignProvider' => $assignProvider
        ]);
    }

    function actionView($id)
    {
        $model = ContactsAssignment::findOne($id);
        if (!$model) {
            throw  new NotFoundHttpException("Không tìm thấy bản ghi!");
        }
        $searchModel = new ManagerSearch();

        $dataProvider = $searchModel->search(array_merge(
            \Yii::$app->request->queryParams,
            [
                'ManagerSearch' => [
                    'phone' => $model->contact_phone,
                    'status' => [
                        ContactsModel::_OK,
                        ContactsModel::_PENDING,
                        ContactsModel::_CANCEL,
                        ContactsModel::_DUPLICATE,
                        ContactsModel::_CALLBACK,
                        ContactsModel::_SKIP,
                        ContactsModel::_NEW
                    ]
                ]
            ]
        ));
        return $this->render("view", [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}