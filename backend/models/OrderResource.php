<?php

namespace backend\models;

use common\helper\Helper;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "order_resource".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $key
 * @property int $created_at
 * @property int $updated_at
 */
class OrderResource extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_resource';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'name',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên nguồn',
            'slug' => 'slug',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    static function name($val)
    {
        $res = self::findOne(['slug' => $val]);
        return $res ? $res->name : 'Not found';
    }
}
