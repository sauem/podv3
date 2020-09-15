<?php

namespace backend\models;


use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use mdm\admin\models\Assignment;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property boolean $is_partner
 * @property int $updated_at
 * @property string|null $country
 * @property string|null $verification_token
 *
 * @property LandingPages[] $landingPages
 */
class UserModel extends User
{
    /**
     * {@inheritdoc}
     */

    public $page_id;
    public $role;
    const _ADMIN = 'admin';
    const _SALE = 'sale';
    const _MARKETING = 'marketing';
    const _LADING = 'lading';
    const _WAREHOUSE = 'warehouse';
    const _PARTNER = 'partner';


    const STATUS = [
        parent::STATUS_ACTIVE => 'Kích hoạt',
        parent::STATUS_INACTIVE => 'Chưa kích hoạt',
        parent::STATUS_DELETED => 'Tạm dừng',
    ];

    const SCENARIO_PASSWORD = "SCENARIO_PASSWORD";

    static function label($status)
    {
        switch ($status) {
            case self::STATUS_DELETED:
                return Html::tag("span", ArrayHelper::getValue(self::STATUS, $status), ['class' => 'badge badge-danger']);
                break;
            case self::STATUS_INACTIVE:
                return Html::tag("span", ArrayHelper::getValue(self::STATUS, $status), ['class' => 'badge badge-secondary']);
                break;
            default:
                return Html::tag("span", ArrayHelper::getValue(self::STATUS, $status), ['class' => 'badge badge-success']);
                break;
        }
    }

    public static function tableName()
    {
        return 'user';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PASSWORD] = ['password_hash'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email', 'role', 'phone_of_day'], 'required'],
            [['status', 'phone_of_day', 'pic'], 'integer'],
            ['is_partner', 'boolean'],
            [['role', 'username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key', 'role'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['country'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['page_id'], 'safe'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tên đăng nhập',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Mật khẩu',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'phone_of_day' => 'Số lượng SDT/ngày',
            'status' => 'Trạng thái',
            'country' => 'Thị trường',
            'created_at' => 'Ngày tạo',
            'pic' => 'Quản lý',
            'page_id' => 'Trang đích',
            'updated_at' => 'Ngày cập nhật',
            'verification_token' => 'Verification Token',
        ];
    }

    /**
     * Gets query for [[LandingPages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLandingPages()
    {
        return $this->hasMany(LandingPages::className(), ['user_id' => 'id']);
    }

    public function getClientPages()
    {
        return $this->hasMany(CustomerPages::className(), ['user_id' => 'id'])->with('page');
    }

    public function setPassword($password)
    {
        parent::setPassword($password); // TODO: Change the autogenerated stub
    }

    public function generateAuthKey()
    {
        return parent::generateAuthKey(); // TODO: Change the autogenerated stub
    }

    public function beforeSave($insert)
    {

        if ($insert) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->setPassword($this->password_hash);
        }
        if ($this->is_partner == 1) {
            if (empty($this->page_id)) {
                $this->addError("page_id", "Landing page bắt buộc!");
                return false;
            }
        }
        if (strtolower($this->role) == UserModel::_SALE && ($this->country == "" || $this->country == null)) {
            $this->addError("country", "Hãy chọn thị trường quản lý cho tài khoản này!");
            return false;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $auth = new Assignment($this->id);
        $auth->assign([$this->role]);
        if ($insert) {
            ActionLog::add("success", "Tạo tài khoản mới <a href='/user/view?id=$this->id'>$this->username</a>");
        }
        ActionLog::add("success", "Cập nhật tài khoản <a href='/user/view?id=$this->id'>$this->username</a>");
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function beforeDelete()
    {
        $auth = new Assignment($this->id);
        $auth->revoke(array_keys(ArrayHelper::getValue($auth->getItems(), 'assigned')));
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterDelete()
    {
        ActionLog::add("success", "Xóa tài khoản <a href='/user/view?id=$this->id'>$this->username</a>");
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function getUserRole()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id']);
    }

    public function getSale()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id'])->where(['auth_assignment.item_name' => UserModel::_SALE]);
    }

    public function getProcessing()
    {
        return $this->hasOne(ContactsAssignment::className(), ['user_id' => 'id'])->where(['contacts_assignment.status' => ContactsAssignment::_PROCESSING]);
    }

    public static function hasCallback($userID = null, $getTime = false)
    {
        if (!$userID) {
            $userID = Yii::$app->user->getId();
        }
        $model = ContactsAssignment::find()
            ->where(['user_id' => $userID])
            ->andWhere(['!=', 'callback_time', ""])
            ->orderBy(['callback_time' => SORT_ASC])->one();
        if (!empty($model->callback_time)) {
            return [
                'created' => Helper::toDate($model->created_at),
                'last_called' => Helper::toDate($model->updated_at),
                'phone' => $model->contact_phone,
                'time' => Helper::caculateDate($model->updated_at, $model->callback_time, $getTime)
            ];
        }
        return false;
    }

    public function afterFind()
    {
        $this->created_at = date("d/m/Y", $this->created_at);
        $this->created_at = date("d/m/Y", $this->updated_at);
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public static function listSales()
    {
        $all = self::find()->innerJoin("auth_assignment", "user.id=auth_assignment.user_id")
            ->where(['auth_assignment.item_name' => self::_SALE])
            ->asArray()->all();
        return ArrayHelper::map($all, "id", "username");
    }

    public static function completed()
    {
        $beginOfDay = strtotime("midnight", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
        $count = ContactsAssignment::find()->where(['user_id' => Yii::$app->user->getId()])
            ->andWhere(['status' => ContactsAssignment::_COMPLETED])
            ->andWhere(['between', 'created_at', $beginOfDay, $endOfDay])
            ->count();
        return $count;
    }

    public function getPending()
    {
        return $this->hasOne(ContactsAssignment::className(), ['user_id' => 'id'])->where([
            'contacts_assignment.status' => ContactsAssignment::_PENDING,
        ])->andWhere(['<>', 'contacts_assignment.callback_time', ""])
            ->orderBy(['contacts_assignment.updated_at' => SORT_DESC]);
    }
}
