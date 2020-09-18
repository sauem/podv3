<?php

namespace backend\models;

use common\helper\Helper;
use Yii;

/**
 * This is the model class for table "contacts_log".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $contact_id
 * @property string|null $status
 * @property string|null $phone
 * @property string $note
 * @property string $customer_note
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $contact_code
 *
 * @property Contacts $contact
 * @property User $user
 */
class ContactsLog extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $callback_time;
    public static function tableName()
    {
        return 'contacts_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['user_id', 'contact_id', 'created_at', 'callback_time', 'updated_at'], 'integer'],
            [['status'], 'string', 'max' => 50],
            [['phone'], 'string'],
            [['note','customer_note','contact_code'], 'string', 'max' => 255],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContactsModel::className(), 'targetAttribute' => ['contact_id' => 'id']],
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
            'user_id' => 'Tài khoản',
            'contact_id' => 'Khách hàng',
            'status' => 'Trạng thái',
            'note' => 'Ghi chú liên hệ',
            'customer_note' => 'Ghi chú nhà vận chuyển',
            'created_at' => 'Ngày liên hệ',
            'updated_at' => 'Updated At',
            'contact_code' => 'Mã liên hệ',
        ];
    }

    /**
     * Gets query for [[Contact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(ContactsModel::className(),['code' => 'contact_code'])->with('logImport');
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $limit = self::find()->where(['contact_code' => $this->contact_code])->count();

            if ($limit >= Helper::setting("limit_call")) {
                $this->addError("contact_id", "Liên hệ quá số lần liên lạc!");
                return false;
            }
            

            $this->status = isset($this->status) ? $this->status : null;
            $contact = ContactsModel::findOne($this->contact_id);
            if($contact){
                $assignment = ContactsAssignment::findOne(['contact_phone' => $contact->phone]);
                $contact->status = $this->status;
                $contact->callback_time = null;
                if ($this->callback_time && $this->callback_time !== null && (
                        $this->status == ContactsModel::_CALLBACK ||
                        $this->status == ContactsModel::_PENDING)) {
                    $contact->callback_time = $this->callback_time;
                    if($assignment){
                        $assignment->callback_time = $this->callback_time;
                        $assignment->status = ContactsAssignment::_PENDING;
                    }
                } else {
                    if ($assignment) {
                        $assignment->callback_time = null;
                        $assignment->status = ContactsAssignment::_PROCESSING;
                    }
                }
                if ($assignment) {
                    $assignment->save();
                }
                $contact->save();
            }

        } else {
            if ($this->status == "") {
                $this->status = null;
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserModel::className(), ['id' => 'user_id']);
    }


    public function afterFind()
    {
        $this->created_at = date("d/m/Y H:i:s", $this->created_at);
        parent::afterFind();
    }

}
