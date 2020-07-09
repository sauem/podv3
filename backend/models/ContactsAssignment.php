<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contacts_assignment".
 *
 * @property int $id
 * @property int $user_id
 * @property string $contact_phone
 * @property string|null $status
 * @property int|null $callback_time
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ContactsAssignment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    const _PENDING = 'pending';
    const _PROCESSING = 'processing';
    const _COMPLETED = 'completed';

    const STATUS = [
        self::_PENDING => 'Chờ xử lý',
        self::_COMPLETED => 'Hoàn thành',
        self::_PROCESSING => 'Đang xử lý'
    ];
    static function label($status){
        switch ($status){
            case self::_COMPLETED:
                $tag = "success";
                break;
            case self::_PENDING:
                $tag = "warning";
                break;
            default:
                $tag = "info";
                break;
        }
        return Html::tag("span",ArrayHelper::getValue(self::STATUS, $status),['class' => "badge badge-" . $tag ]);
    }
    public static function tableName()
    {
        return 'contacts_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'contact_phone'], 'required'],
            [['user_id', 'callback_time', 'created_at', 'updated_at'], 'integer'],
            [['contact_phone'], 'string', 'max' => 15],
            [['status'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'contact_phone' => 'Contact Phone',
            'status' => 'Status',
            'callback_time' => 'Callback Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getContacts(){
        return $this->hasMany(ContactsModel::className(),['phone' => 'contact_phone']);
    }


}
