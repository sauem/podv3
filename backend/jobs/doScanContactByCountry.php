<?php


namespace backend\jobs;


use backend\models\AuthAssignment;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\UserModel;
use common\helper\Helper;
use yii\helpers\ArrayHelper;

class doScanContactByCountry
{
    static function apply()
    {
        $phones = ContactsModel::find()->addSelect(['phone', 'country'])->groupBy(['phone', 'country'])->all();

        $users = AuthAssignment::find()->with('user')
            ->where(['item_name' => UserModel::_SALE])
            ->all();

        foreach ($users as $k => $user) {
            $currentUser = $user->user[0];
            // bỏ qua sale đủ số lượng SĐT trong ngày
            if (self::hasLimit($currentUser)) {
                // Kiểm tra số điện thoại gọi lại
                self::rollbackPending($currentUser);
                //
                continue;
            }
            //chưa có liên hệ nào được phân bổ
            foreach ($phones as $p => $phone) {
                $phoneNumber = $phone->phone;
                $phoneCountry = $phone->country;
                // Bỏ qua SĐT nếu được phân bổ
                if (self::exitsPhone($phoneNumber)) {
                    self::checkCompleted($phoneNumber, $currentUser->id);
                    self::rollbackPending($currentUser);
                    continue;
                }
                // Nếu user chưa có số điện thoại trong trạng thái : PROCESSING
                $status = ContactsAssignment::_PROCESSING;
                if (self::hasProcessing($currentUser)) {
                    $status = ContactsAssignment::_PENDING;
                }
                // phân bổ số điện thoại cho user
                if ($currentUser->country === $phoneCountry) {
                    self::assignPhoneToUser($phoneNumber, $currentUser->id, $phoneCountry, $status);
                }
            }
        }
        return "success";
    }

    static function hasProcessing($user)
    {
        $model = ContactsAssignment::find()
            ->where(['user_id' => $user])
            ->andWhere(['status' => ContactsAssignment::_PROCESSING])
            ->count();
        if ($model > 0) {
            return true;
        }
        return false;
    }

    static function assignPhoneToUser($phone, $user_id, $country, $status = ContactsAssignment::_PENDING)
    {
        $model = new ContactsAssignment;
        $model->contact_phone = $phone;
        $model->user_id = $user_id;
        $model->country = $country;
        $model->status = $status;
        return $model->save();
    }

    static function exitsPhone($phone)
    {
        $model = ContactsAssignment::findOne(['contact_phone' => $phone]);
        if ($model) {
            return true;
        }
        return false;
    }

    static function hasLimit($user)
    {
        $beginOfDay = strtotime("midnight", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
        $count = ContactsAssignment::find()->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_ASC])
            ->andFilterWhere([
                'between', 'created_at', $beginOfDay, $endOfDay
            ])->count();
        return $count >= $user->phone_of_day;
    }

    static function rollbackPending($user)
    {
        $now = time();
        $model = ContactsAssignment::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['is not', 'callback_time', new \yii\db\Expression('null')])
            ->orderBy(['callback_time' => SORT_ASC])->all();

        if ($model) {
            foreach ($model as $item) {
                $data = [
                    'phone' => $item->contact_phone,
                    'time' => Helper::caculateDate($item->updated_at, $item->callback_time, true)
                ];
                if ($now >= $data['time']) {
                    $item->callback_time = null;
                    $item->status = ContactsAssignment::_PROCESSING;
                    if (!$item->save()) {
                        return false;
                    }
                }
            }
            return true;
        }
    }

    static function hasNewContact($phone)
    {
        return ContactsModel::hasNewContact($phone);
    }

    static function checkCompleted($phone, $user_id)
    {
        $model = ContactsAssignment::find()
            ->where(['contact_phone' => $phone, 'user_id' => $user_id])
            ->andWhere(['status' => ContactsAssignment::_COMPLETED])
            ->one();
        if ($model) {
            if (self::hasNewContact($phone)) {
                $model->status = ContactsAssignment::_PENDING;
                echo "Có liện mới tại số điện thoại $phone";
                return $model->save();
            }
        }
    }

}