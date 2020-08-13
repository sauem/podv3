<?php


namespace backend\controllers;


use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use backend\models\ContactsLog;
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
        $callbackProdvider = $searchModel->search(array_merge(\Yii::$app->request->queryParams, [
            'ContactsAssignmentSearch' => [
                'user_id' => \Yii::$app->user->getId(),
                'callback' =>  true
            ]
        ]));
        return $this->render("index", [
            'assignProvider' => $assignProvider,
            'callbackProvider' => $callbackProdvider
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

        $contactHistories = new ActiveDataProvider([
            'query' => ContactsLog::find()
                ->rightJoin('contacts', 'contacts.id=contacts_log.contact_id')
                ->andWhere(['contacts_log.user_id' => \Yii::$app->user->getId(),])
                ->andWhere(['contacts.phone' => $model->contact_phone])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return $this->render("view", [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'histories' => $contactHistories
        ]);
    }
}