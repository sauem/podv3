<?php

namespace backend\models;
use backend\jobs\doScanContact;
use backend\jobs\scanNewContact;
use backend\models\UserModel;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $customer_name
 * @property string $customer_phone
 * @property string|null $customer_email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $district
 * @property int|null $zipcode
 * @property string|null $country
 * @property float|null $sale
 * @property float|null $sub_total
 * @property float|null $total
 * @property string|null $order_note
 * @property int|null $user_id
 * @property int|null $contact_id
 * @property string|null $status
 * @property string|null $status_note
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property OrdersItems[] $ordersItems
 */
class OrdersModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public $contact_id;
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_name', 'customer_phone','address','zipcode'], 'required'],
            [['zipcode', 'user_id', 'created_at', 'updated_at','payment_method'], 'integer'],
            [['sale', 'sub_total', 'total'], 'number'],
            [['customer_name', 'address', 'city', 'district', 'country', 'order_note', 'status_note','contact_id','vendor_note'], 'string', 'max' => 255],
            [['customer_phone'], 'string', 'max' => 15],
            [['customer_email'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 25],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserModel::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if($insert){

            $contact = explode(",",$this->contact_id);
            if(is_array($contact)){
                foreach ($contact as $id){
                    $orderRelation = new OrdersContacts;
                    $orderRelation->contact_id = $id;
                    $orderRelation->order_id = $this->id;
                    $orderRelation->user_id = Yii::$app->user->getId();
                    $orderRelation->save();
                }

            }else{
                $orderRelation = new OrdersContacts;
                $orderRelation->contact_id = $this->contact_id;
                $orderRelation->order_id = $this->id;
                $orderRelation->user_id = Yii::$app->user->getId();
                $orderRelation->save();
            }
            ActionLog::add("success", "Tạo đơn hàng $this->id");
            ContactsModel::updateAll(['status' => ContactsModel::_OK],['id' =>  $contact]);
        }
        ActionLog::add("success", "Cập nhật đơn hàng $this->id");
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_name' => 'Tên kách hàng',
            'customer_phone' => 'Số điện thoại',
            'customer_email' => 'Email',
            'address' => 'Địa chỉ',
            'city' => 'Thành phố',
            'district' => 'Quận/Huyện',
            'zipcode' => 'Zipcode',
            'country' => 'Quốc gia',
            'sale' => 'Sale',
            'sub_total' => 'Sub Total',
            'total' => 'Total',
            'order_note' => 'Ghi chú đơn hàng',
            'user_id' => 'Người tạo đơn',
            'status' => 'Trạng thái',
            'payment_method' => 'Phuơng thức thanh toán',
            'bill_transfer' => 'Hoá đơn chuyển khoản',
            'shipping_price' => 'Gía vận chuyển',
            'status_note' => 'Ghi chú xác nhận',
            'created_at' => 'Ngày tạo đơn',
            'updated_at' => 'Ngày cập nhật',
        ];
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

    /**
     * Gets query for [[OrdersItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(OrdersItems::className(), ['order_id' => 'id'])->with('product');
    }
    public function getContacts(){
        return $this->hasMany(OrdersContacts::className(),['order_id' => 'id'])->with('contact');
    }

    public function getPayment(){
        return $this->hasOne(Payment::className(),['id' => 'payment_method']);
    }
}
