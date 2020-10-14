<?php

namespace backend\models;

use common\helper\Helper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $country
 * @property int|null $status
 * @property string|null $note
 * @property int $created_at
 * @property int $updated_at
 */
class Warehouse extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const ACTIVE = 1;
    const DEACTIVE = 0;
    const STATUS = [
        self::ACTIVE => 'Hoạt động',
        self::DEACTIVE => 'Ngừng hoạt động'
    ];
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'note', 'country'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function labelStatus($status) {
        $color = 'secondary';
        switch ($status){
            case self::ACTIVE:
                $color = 'success';
                break;
            case self::DEACTIVE:
                $color = 'secondary';
                break;
            default:
                break;
        }
        return "<span class='badge badge-$color'>".ArrayHelper::getValue(self::STATUS, $status)."</span>";
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên kho',
            'country' => 'Thị trường',
            'status' => 'Trạng thái',
            'note' => 'Ghi chú',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
