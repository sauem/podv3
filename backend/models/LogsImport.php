<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "logs_import".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $line
 * @property string|null $message
 * @property string|null $name
 * @property int $created_at
 * @property int $updated_at
 */
class LogsImport extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs_import';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['message', 'user_id'], 'required'],
            [['line', 'message', 'name'], 'string', 'max' => 255],
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
            'line' => 'Line',
            'message' => 'Message',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
