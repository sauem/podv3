<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "form_info".
 *
 * @property int $id
 * @property int|null $category_id
 * @property string|null $content
 * @property float|null $revenue
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 * @property FormInfoSku[] $formInfoSkus
 */
class FormInfo extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['revenue'], 'number'],
            [['created_at', 'updated_at'], 'required'],
            [['content'], 'string', 'max' => 255],
            [['content'], 'unique'],
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
            'category_id' => 'Category ID',
            'content' => 'Content',
            'revenue' => 'Revenue',
            'created_at' => 'Created At',
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
     * Gets query for [[FormInfoSkus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormInfoSkus()
    {
        return $this->hasMany(FormInfoSku::className(), ['info_id' => 'id']);
    }
}
