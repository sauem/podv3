<?php

namespace backend\models;

use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $email
 * @property string|null $address
 * @property int|null $zipcode
 * @property string|null $option
 * @property string|null $ip
 * @property string|null $note
 * @property string|null $link
 * @property string|null $short_link
 * @property string|null $utm_source
 * @property string|null $utm_medium
 * @property string|null $utm_content
 * @property string|null $utm_term
 * @property string|null $utm_campaign
 * @property string|null $host
 * @property string|null $hashkey
 * @property string|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $callback_time
 *
 * @property ContactsLog[] $contactsLogs
 */
class ContactsModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $street;


    const _PENDING = 'pending';
    const _OK = 'ok';
    const _CALLBACK = 'callback';
    const _CANCEL = 'cancel';
    const _SKIP = 'skip';
    const _DUPLICATE = 'duplicate';
    const _NUMBER_FAIL = 'number_fail';
    const _NEW = null;

    const STATUS = [
        self::_OK => 'Thành công',
        self::_CALLBACK => 'Hẹn gọi lại',
        self::_PENDING => 'Thuê bao',
        self::_CANCEL => 'Hủy',
        self::_DUPLICATE => 'Trùng số',
        self::_NUMBER_FAIL => 'Sai số',
        self::_SKIP => 'Bỏ qua',
        self::_NEW => 'Đợi xử lý'
    ];

    static function label($status)
    {
        switch ($status) {
            case self::_OK:
                $tag = "success";
                break;
            case self::_CALLBACK:
            case self::_PENDING:
                $tag = "warning";
                break;
            case self::_NUMBER_FAIL:
            case self::_DUPLICATE:
            case self::_CANCEL:
                $tag = "secondary";
                break;
            case self::_SKIP:
                $tag = "danger";
                break;
            default:
                $tag = "info";
                break;
        }
        return Html::tag("span", ArrayHelper::getValue(self::STATUS, $status), ['class' => "badge badge-" . $tag]);
    }

    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required', 'message' => '{attribute} không được để trống!'],
            [['address', 'option', 'link', 'short_link', 'street'], 'string'],
            [['zipcode', 'created_at', 'updated_at', 'callback_time'], 'integer'],
            [['name', 'note', 'utm_source', 'utm_medium', 'utm_content', 'utm_term', 'utm_campaign', 'host', 'hashkey'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 100],
           // [['hashkey'], 'unique', 'message' => 'Liên hệ đã tồn tại với lựa chọn option tương ứng!'],
            [['ip', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            //$this->address = $this->address;
            $this->hashkey = md5($this->phone . $this->option);
            $this->short_link = Helper::getHost($this->link);
            $this->host = Helper::getHost(Yii::$app->request->getHostInfo());

            if (self::findOne(['hashkey' => $this->hashkey])) {
                $this->addError("hashkey", "Liên hệ đã tồn tại với lựa chọn option tương ứng!");
                return false;
            }
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'phone' => 'Số điện thoại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'zipcode' => 'Zipcode',
            'option' => 'Option',
            'ip' => 'Ip',
            'note' => 'Ghi chú',
            'link' => 'Link',
            'callback_time' => 'Giờ gọi lại',
            'short_link' => 'Host link',
            'utm_source' => 'Utm Source',
            'utm_medium' => 'Utm Medium',
            'utm_content' => 'Utm Content',
            'utm_term' => 'Utm Term',
            'utm_campaign' => 'Utm Campaign',
            'host' => 'Host',
            'hashkey' => 'Hashkey',
            'status' => 'Trạng hái',
            'created_at' => 'Ngày nhận',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    /**
     * Gets query for [[ContactsLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContactsLogs()
    {
        return $this->hasMany(ContactsLog::className(), ['contact_id' => 'id']);
    }

    function getPage()
    {
        return $this->hasOne(LandingPages::className(), ['link' => 'short_link'])->with('product');
    }

    public static function listPhoneContact()
    {
        $phone = ContactsModel::find()->groupBy('phone')->asArray()->all();
        return ArrayHelper::getColumn($phone, 'phone');
    }

    public function getAssignment()
    {
        return $this->hasOne(ContactsAssignment::className(), ['contact_phone' => 'phone'])
            ->where(['contacts_assignment.status' => ContactsAssignment::_PROCESSING])->with('user');
    }

    public function getSaleAssign()
    {
        return $this->hasOne(ContactsAssignment::className(), ['contact_phone' => 'phone'])
            ->with('user');
    }

    public function getSumContact()
    {
        return $this->hasMany(ContactsModel::className(), ['phone' => 'phone'])->from(self::tableName());
    }

    public function afterSave($insert, $changedAttributes)
    {
        static::updateCompleteAndNextProcess();
        if($insert){
            ActionLog::add("success","Thêm liên hệ mới $this->id");
        }
        ActionLog::add("success","Cập nhật trạng thái liên hệ $this->id");
        parent::afterSave($insert, $changedAttributes);
    }

    static function hasCompeleted($phone)
    {
        $count = ContactsModel::find()
            ->where(['phone' => $phone, "status" => ContactsModel::_NEW])->count();
        if ($count < 1) {
            return true;
        }
        return false;
    }

    static function hasNewContact($phone)
    {
        $count = ContactsModel::find()
            ->where(['phone' => $phone])
            ->andWhere(["is", 'callback_time', new \yii\db\Expression('null')])
            ->andWhere(["is", "status", new \yii\db\Expression('null')]);

        if ($count->count() > 0) {
            return true;
        }
        return false;
    }

    static function updateCompleteAndNextProcess(){
        $processing = ContactsAssignment::findOne(['user_id' => Yii::$app->user->getId(), 'status' => ContactsAssignment::_PROCESSING]);
        if ($processing && static::hasCompeleted($processing->contact_phone)) {
            if (!ContactsAssignment::nextAssignment()) {
                Yii::$app->session->setFlash("error", "Hiện tại đã hết liên hệ, xin hãy chờ!");
            } else {
                Yii::$app->session->setFlash("success", "Số điện thoại mới được áp dụng");
            }
            $processing->status = ContactsAssignment::_COMPLETED;
            if(!$processing->save()){
                return Helper::firstError($processing);
            }
        }
    }
}
