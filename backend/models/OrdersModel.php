<?php

namespace backend\models;
use backend\models\UserModel;
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
            [['zipcode', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['sale', 'sub_total', 'total'], 'number'],
            [['customer_name', 'address', 'city', 'district', 'country', 'order_note', 'status_note','contact_id'], 'string', 'max' => 255],
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

                ContactsModel::updateAll(['status' => ContactsModel::_OK],['IN' ,'id', $contact]);
            }else{
                $orderRelation = new OrdersContacts;
                $orderRelation->contact_id = $this->contact_id;
                $orderRelation->order_id = $this->id;
                $orderRelation->user_id = Yii::$app->user->getId();
                $orderRelation->save();
            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_name' => 'Customer Name',
            'customer_phone' => 'Customer Phone',
            'customer_email' => 'Customer Email',
            'address' => 'Address',
            'city' => 'City',
            'district' => 'District',
            'zipcode' => 'Zipcode',
            'country' => 'Country',
            'sale' => 'Sale',
            'sub_total' => 'Sub Total',
            'total' => 'Total',
            'order_note' => 'Order Note',
            'user_id' => 'User ID',
            'status' => 'Status',
            'status_note' => 'Status Note',
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
        return $this->hasOne(UserModel::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[OrdersItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersItems()
    {
        return $this->hasMany(OrdersItems::className(), ['order_id' => 'id']);
    }
}
