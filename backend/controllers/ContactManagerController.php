<?php


namespace backend\controllers;


use backend\models\ContactsAssignment;
use backend\models\ContactsAssignmentSearch;
use backend\models\ContactsLog;
use backend\models\ContactsModel;
use backend\models\ContactsSearchModel;
use backend\models\ManagerSearch;
use common\helper\Helper;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class ContactManagerController extends BaseController
{
    function actionIndex($start = null, $end = null)
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
                'callback' => true
            ]
        ]));


        $query = ContactsLog::find()
            ->joinWith('user')
            ->select([
                'contacts_log.status',
                'contacts_log.contact_code',
                'contacts_log.user_id',
                'contacts_log.created_at',
                'user.username as sale',
                'contacts_log.user_id',
                'SUM( IF (contacts_log.status = "ok", 1,0)) as ok',
                'SUM( IF (contacts_log.status = "pending", 1,0)) as pending',
                'SUM( IF (contacts_log.status = "cancel", 1,0)) as cancel',
                'SUM( IF ( contacts_log.status = "number_fail" || contacts_log.status = "duplicate" || contacts_log.status = "skip" , 1,0)) as failed',
                // Đếm nếu status = "callback" và contact code đó chỉ xuất hiện 1 lần
                'SUM( CASE WHEN contacts_log.status = "callback"  THEN 1 else 0 END) as callback',
            ])
            ->groupBy(['contacts_log.user_id']);

        $beginOfDay = strtotime("midnight", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay);

        if ($start && $end) {
            $query->filterWhere(['between', 'contacts_log.created_at', $start, $end]);
        } else {
            $query->filterWhere(['between', 'contacts_log.created_at', $beginOfDay, $endOfDay]);
        }
        $query->filterWhere(['contacts_log.user_id' => \Yii::$app->user->getId()]);
        $query = $query->asArray()->all();

        $brankProvider = new ArrayDataProvider([
            'allModels' => $query
        ]);

        return $this->render("index", [
            'assignProvider' => $assignProvider,
            'callbackProvider' => $callbackProdvider,
            'brankProvider' => $brankProvider
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