<?php

namespace backend\models;

use backend\jobs\scanNewContact;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;

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
 * @property int $code
 * @property string|null $country
 * @property int|null $register_time
 *
 * @property ContactsLog[] $contactsLogs
 */
class ContactsModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $street;

    const _CAPTURE = "capture form";
    const _ADS = 'ads';
    const TYPE = [
        self::_CAPTURE => 'Capture form',
        self::_ADS => 'ads'
    ];
    const _PENDING = 'pending';
    const _OK = 'ok';
    const _CALLBACK = 'callback';
    const _CANCEL = 'cancel';
    const _SKIP = 'skip';
    const _DUPLICATE = 'duplicate';
    const _NUMBER_FAIL = 'number_fail';
    const _NEW = null;

    const STATUS = [
        self::_NEW => 'Đợi xử lý',
        self::_OK => 'Thành công',
        self::_CALLBACK => 'Hẹn gọi lại',
        self::_PENDING => 'Thuê bao',
        self::_CANCEL => 'Hủy',
        self::_DUPLICATE => 'Trùng số',
        self::_NUMBER_FAIL => 'Sai số',
        self::_SKIP => 'Bỏ qua'
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

    const SCENARIO_API = 'SCENARIO_API';

    public function scenarios()
    {
        $scenario = parent::scenarios();
        $scenario[self::SCENARIO_API] = ['phone', 'name', 'zipcode', 'option', 'note'];
        return $scenario;
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
            [['address', 'option', 'link', 'short_link', 'street', 'code', 'note', 'country', 'type'], 'string'],
            [['register_time'], 'safe'],
            [['zipcode', 'created_at', 'updated_at', 'callback_time'], 'integer'],
            [['name', 'utm_source', 'utm_medium', 'utm_content', 'utm_term', 'utm_campaign', 'host', 'hashkey'], 'string', 'max' => 255],
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
            $maxIDNumber = ContactsModel::find()->max('id');
            if (!$maxIDNumber) {
                $maxIDNumber = 0;
            }

            $this->short_link = Helper::getHost($this->link);
            $this->hashkey = md5($this->phone . $this->short_link . $this->option);
            $this->host = Helper::getHost(Yii::$app->request->getHostInfo());
            $this->country = $this->country ? $this->country : Helper::findCountryFromZipcode($this->zipcode, $this->link);

            $this->code = (!$this->code || strpos($this->code, $this->country)) ? Helper::makeCodeIncrement($maxIDNumber, $this->country) : $this->code;

            $this->register_time = empty($this->register_time) ? time() : Helper::convertTime($this->register_time);

            if (self::checkExists($this->hashkey)) {
                $this->addError("hashkey", "Liên hệ đã tồn tại với lựa chọn option tương ứng!");
                return false;
            }
            if (self::checkExistCapture($this->phone, $this->short_link)) {
                $this->addError("type", "Liên hệ đã tồn tại với phân loại capture form!");
                return false;
            }
//            if (!$this->country ) {
//                $this->addError("country", "Quốc gia rỗng!");
//                return false;
//            }
        }

        if ($this->status === "" || empty($this->status)) {
            $this->status = null;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    static function checkExistCapture($phone, $link)
    {
        $count = self::find()->where(['phone' => $phone, 'short_link' => $link])
            ->andFilterWhere(['LIKE', 'type', self::_CAPTURE])->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    static function checkExists($haskey)
    {
        if (self::findOne(['hashkey' => $haskey])) {
            return true;
        }
        return false;
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
            'country' => 'Quốc gia',
            'hashkey' => 'Hashkey',
            'status' => 'Trạng hái',
            'register_time' => 'Ngày đặt hàng',
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
        return $this->hasMany(ContactsLog::className(), ['contact_code' => 'code']);
    }

    public function getLogImport()
    {
        return $this->hasMany(ContactsLogImport::className(), ['phone' => 'phone']);
    }

    function getPage()
    {
        return $this->hasOne(LandingPages::className(), ['link' => 'short_link'])
            ->with('user')->with('category')->with('product');
    }

    public static function listPhoneContact()
    {
        $phone = ContactsModel::find()->groupBy('phone')->asArray()->all();
        return ArrayHelper::getColumn($phone, 'phone');
    }

    public function getAssignment()
    {
        return $this->hasOne(ContactsAssignment::className(), ['contact_phone' => 'phone'])
            ->where(['contacts_assignment.status' => [ContactsAssignment::_PROCESSING, ContactsAssignment::_PENDING, ContactsAssignment::_COMPLETED]])->with('user');
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
        static::updateCompleteAndNextProcess($this->phone);
        if ($insert) {
            ActionLog::add("success", "Thêm liên hệ mới <a href='/contacts/view?id=$this->id'>$this->code</a>");
        } else {
            ActionLog::add("success", "Cập nhật trạng thái liên hệ <a href='/contacts/view?id=$this->id'>$this->code</a>");
            Yii::$app->queue->push(new scanNewContact());
        }
        parent::afterSave($insert, $changedAttributes);
    }

    static function hasCompeleted($phone)
    {
        $count = ContactsModel::find()
            ->where(['phone' => $phone])
            ->andWhere(['IN', 'status', [ContactsModel::_NEW, ContactsModel::_PENDING, ContactsModel::_CALLBACK]])
            ->count();
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

    static function updateCompleteAndNextProcess($phone = null)
    {
        $processing = ContactsAssignment::findOne(['user_id' => Yii::$app->user->getId(), 'contact_phone' => $phone, 'status' => ContactsAssignment::_PROCESSING]);
        if ($processing && static::hasCompeleted($phone)) {
            if (!ContactsAssignment::nextAssignment($phone)) {
                Helper::showMessage("Hiện tại đã hết liên hệ, xin hãy chờ!", "error");
                Yii::$app->session->setFlash("error", "Hiện tại đã hết liên hệ, xin hãy chờ!");
            } else {
                Helper::showMessage("Số điện thoại mới được áp dụng!");
                Yii::$app->session->setFlash("success", "Số điện thoại mới được áp dụng");
            }
            $processing->status = ContactsAssignment::_COMPLETED;
            if (!$processing->save()) {
                return Helper::firstError($processing);
            }
        }
    }

    public function getFormInfo()
    {
        return $this->hasOne(FormInfo::className(), ['content' => 'option']);
    }

    public function getOrder()
    {
        return $this->hasOne(OrdersModel::className(), ['code' => 'code']);
    }

    public function getLatestContact()
    {
        return $this->hasOne(ContactsModel::className(), ['phone' => 'phone'])->orderBy(['register_time' => SORT_DESC]);
    }

    public static function hasDuplicate($hashkey)
    {
        $count = self::findAll(['hashkey' => $hashkey]);
        if (sizeof($count) > 1) {
            return true;
        }
        return false;
    }
}
