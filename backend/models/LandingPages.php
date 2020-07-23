<?php

namespace backend\models;

use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use Yii;

/**
 * This is the model class for table "landing_pages".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property int|null $category_id
 * @property int|null $product_id
 * @property int|null $user_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 * @property Products $product
 * @property User $user
 */

use common\models\User;
class LandingPages extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'landing_pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'link','category_id','product_id'], 'required'],
            [['link'], 'string'],
            [['category_id', 'product_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesModel::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsModel::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'name' => 'Tên trang',
            'link' => 'Link',
            'category_id' => 'Danh mục',
            'product_id' => 'Sản phẩm',
            'user_id' => 'Quản lý',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CategoriesModel::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductsModel::className(), ['id' => 'product_id'])->with('category');
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
        return $this->hasMany(ContactsModel::className(),['short_link' =>'link']);
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->link = Helper::getHost($this->link);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public function afterSave($insert, $changedAttributes)
    {
        if($insert){
            ActionLog::add("success", "Tạo landing page $this->name");
        }
        ActionLog::add("success", "Cập nhật Tạo landing page $this->name");
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
    public function afterDelete()
    {
        ActionLog::add("success", "Xóa landing page $this->name");
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
