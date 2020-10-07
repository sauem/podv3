<?php


namespace backend\controllers;


use backend\models\AuthAssignment;
use backend\models\ContactsModel;
use backend\models\LandingPages;
use backend\models\ProductsModel;
use backend\models\UserModel;
use common\helper\Helper;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class AnalyticsController extends BaseController
{
    function actionIndex()
    {
        $marketer = LandingPages::find()
            ->addSelect(['marketer'])
            ->groupBy(['marketer'])
            ->asArray()->all();
        $marketer = ArrayHelper::map($marketer, 'marketer', 'marketer');
        $source = ContactsModel::find()->addSelect([
            'type'
        ])->distinct('type')->asArray()->all();
        $source = ArrayHelper::map($source, 'type', 'type');
        $product = ProductsModel::find()->addSelect(['name', 'sku'])
            ->distinct('sku')->asArray()->all();
        $product = ArrayHelper::map($product, 'sku', 'name');

        $users = AuthAssignment::find()->with('user')->where(['item_name' => UserModel::_SALE])->asArray()->all();

        $sale = ArrayHelper::map($users, 'user_id', function($item) {
            return $item['user'][0]['username'];
        });

        return $this->render("index", [
            'marketer' => $marketer,
            'source' => $source,
            'product' => $product,
            'sale' => $sale
        ]);
    }
}