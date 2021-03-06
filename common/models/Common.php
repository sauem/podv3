<?php


namespace common\models;


use yii\base\Model;

class Common extends Model
{
    public $backup_time;
    public $delete_time;
    public $rescan_contact_time;
    public $drive_id;
    public $map_api;
    public $limit_call;
    public $contact_sheet;

    public function rules()
    {
        return [
            [[
                'backup_time', 'delete_time', 'rescan_contact_time','limit_call'
            ], 'integer'],
            [['drive_id','map_api','contact_sheet'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'backup_time' => 'Lưu dữ liệu tự động (giờ)',
            'delete_time' => 'Xóa dữ liệu cũ (giờ)',
            'rescan_contact_time' => 'Auto phân bổ SĐT (giờ)',
            'drive_id' => 'Thư mục driver lưu dữ liệu',
            'map_api' => 'API google Maps',
            'limit_call' => 'Giới hạn cuộc gọi',
            'contact_sheet' => 'ID google sheet liên hệ'
        ];
    }


}