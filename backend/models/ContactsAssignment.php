<?php

namespace backend\models;

use common\helper\Helper;
use Yii;

/**
 * This is the model class for table "contacts_assignment".
 *
 * @property int $id
 * @property int $user_id
 * @property string $contact_phone
 * @property string|null $status
 * @property string $country
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

    static function label($status)
    {
        switch ($status) {
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
        return Html::tag("span", ArrayHelper::getValue(self::STATUS, $status), ['class' => "badge badge-" . $tag]);
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
            [['user_id', 'contact_phone','country'], 'required'],
            [['user_id', 'callback_time', 'created_at', 'updated_at'], 'integer'],
            [['contact_phone'], 'string', 'max' => 15],
            [['status','country'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserModel::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'country' => 'Thị trường',
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
        return $this->hasOne(UserModel::className(), ['id' => 'user_id'])->with('userRole');
    }

    public function getContacts()
    {
        return $this->hasMany(ContactsModel::className(), ['phone' => 'contact_phone'])->with('contactsLogs');
    }

    public static function nextAssignment()
    {
        $assignment = ContactsAssignment::find()->where(['user_id' => Yii::$app->user->getId()])
            ->andWhere(['status' => ContactsAssignment::_PENDING, 'callback_time' => null])
            ->orWhere(['status' => ContactsAssignment::_PROCESSING])
            ->one();
        if ($assignment) {
            $assignment->status = ContactsAssignment::_PROCESSING;
            Yii::$app->session->setFlash("success", "Số điện thoại mới được áp dụng!");
            return $assignment->save();
        }
        return false;
    }

    public function beforeSave($insert)
    {
        if (!$insert) {
            if (!Yii::$app instanceof Yii\console\Application) {
                if ($this->callback_time && !self::nextAssignment()) {
                    Yii::$app->session->setFlash("error", "Hiện tại đã hết liên hệ,\n xin hãy chờ gọi lại số điện thoại này sau {$this->callback_time} giờ nữa!");
                }
            }

        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function getPhones()
    {
        $phones = self::find()->where(['user_id' => Yii::$app->user->getId()])
            ->addSelect("contact_phone")->distinct()->asArray()->all();
        return ArrayHelper::getColumn($phones, "contact_phone");
    }

    static function prevAssignment()
    {
        $phone = self::find()->where([
            'user_id' => Yii::$app->user->getId(),
        ])->orderBy(['updated_at' => SORT_DESC])->one();
        if ($phone) {
            return $phone->contact_phone;
        }
        return null;
    }

    static function lastStatusAssignment()
    {
        $phone = self::find()->where([
            'user_id' => Yii::$app->user->getId(),
        ])->andWhere(['IN', 'status' , [self::_PROCESSING]])
            ->orderBy(['updated_at' => SORT_DESC])->one();
        if (!$phone) {
            return ContactsAssignment::_PROCESSING;
        }
        return $phone->getAttribute("status");
    }
}
