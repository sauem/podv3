<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property float|null $regular_price
 * @property float|null $sale_price
 * @property int|null $category_id
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 */
class ProductsModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sku', 'category_id'], 'required'],
            [['regular_price', 'sale_price'], 'number'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'sku', 'description'], 'string', 'max' => 255],
            [['sku'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriesModel::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên sản phẩm',
            'sku' => 'Mã sản phẩm',
            'regular_price' => 'Giá gốc',
            'sale_price' => 'Giá giảm',
            'category_id' => 'Danh mục',
            'description' => 'Mô tả',
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
    static function select(){
        $all = self::find()->all();
        return ArrayHelper::map($all,'id','name');
    }

    public function afterFind()
    {
        $this->regular_price = number_format($this->regular_price,2,'.',',') ."đ";
        $this->category_id = CategoriesModel::findOne($this->category_id)->name;
        parent::afterFind();
    }
}
