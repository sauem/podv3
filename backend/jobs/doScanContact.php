<?php

namespace backend\jobs;

use backend\models\AuthAssignment;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\UserModel;
use cakebake\actionlog\model\ActionLog;
use common\helper\Helper;
use yii\helpers\ArrayHelper;

class doScanContact
{
    public static function apply()
    {
        $phones = ContactsModel::find()->addSelect(['phone'])->distinct()->asArray()->all();
        $phones = ArrayHelper::getColumn($phones, 'phone');
        $users = AuthAssignment::find()->with('user')->where(['item_name' => UserModel::_SALE])->asArray()->all();
        $users = ArrayHelper::getColumn($users, 'user_id');

        foreach ($users as $user) {
            $count = self::countAssignUser($user);

            if ($count >= 2) {
                self::openCallback($user);
              //  self::pendingStatus($user);
                continue;
            } else {
                if (self::isLimitOfDay($user)) {
                    self::openCallback($user);
                    self::pendingStatus($user);
                    continue;
                }
                foreach ($phones as $k => $phone) {
                    if (self::isLimitOfDay($user)) {
                        continue;
                    }
                    $exitStatus = self::getStatusUser($user, $phone);
                    if ($exitStatus) {
                        self::changeStatusPending($exitStatus);
                        continue;
                    } else {
                        if (!self::phoneExitsts($phone) && self::countAssignUser($user) < 2) {
                            $count = self::countAssignUser($user);
                            switch ($count) {
                                case 1:
                                    if (!self::hasCallback($user)) {
                                        self::assignUser($phone, $user, ContactsAssignment::_PENDING);
                                    } else {
                                        self::assignUser($phone, $user, ContactsAssignment::_PROCESSING);
                                    }
                                    break;
                                default:
                                    self::assignUser($phone, $user, ContactsAssignment::_PROCESSING);
                                    break;
                            }
                        } else {
                            continue;
                        }
                    }
                }
            }
        }
        return "done!";
    }

    static function hasTime($user_id)
    {

        $assign = ContactsAssignment::find()->where(['user_id' => $user_id, "status" => ContactsAssignment::_PENDING])
            ->andWhere(['is not', 'callback_time', new \yii\db\Expression('null')])
            ->orderBy(["callback_time" => SORT_ASC])
            ->one();
        if (!$assign) {
            return true;
        }
        return self::openUserCallback($assign);
    }

    static function changeStatusPending(ContactsAssignment $exitStatus)
    {

        if ($exitStatus) {
            if ($exitStatus->status = ContactsAssignment::_COMPLETED) {
                if (self::checkNewContact($exitStatus->contact_phone)) {
                    $exitStatus->status = ContactsAssignment::_PROCESSING;
                }
            } elseif ($exitStatus->status == ContactsModel::_PENDING && !empty($exitStatus->callback_time)) {
                self::openUserCallback($exitStatus);
            }
            else {
                $exitStatus->status = ContactsAssignment::_PROCESSING;
            }
            return $exitStatus->save();
        }
    }

    static function openUserCallback(ContactsAssignment $user)
    {

        $now = time();
        $nextTime = Helper::caculateDate($user->updated_at, $user->callback_time, true);
        if ($now >= ArrayHelper::getValue($nextTime, 'time')) {
            $user->status = ContactsAssignment::_PROCESSING;
            $user->callback_time = null;
            return $user->save();
        }
        return false;
    }

    static function checkNewContact($phone)
    {
        // check have new contact
        return ContactsModel::hasNewContact($phone);
    }

    static function phoneExitsts($phone)
    {
        $exitsts = ContactsAssignment::find()->where(['contact_phone' => $phone])->count();
        if ($exitsts > 0) {
            return true;
        }
        return false;
    }

    static function assignUser($phone, $user, $status = ContactsAssignment::_PROCESSING)
    {
        $model = new ContactsAssignment;
        $model->load([
            'user_id' => $user,
            'contact_phone' => $phone,
            'status' => $status
        ], '');
        $model->save();

       // ActionLog::add("success",  "Số điện thoại $model->contact_phone được phân bổ cho tài khoản $user");
    }

// số lượng được assign không có trạng thái completed
    static function countAssignUser($user)
    {
        $count = ContactsAssignment::find()
            ->where(['status' => [ContactsAssignment::_PENDING, ContactsAssignment::_PROCESSING]])
            ->andWhere(['user_id' => $user])->count();
        return $count;
    }

    static function getStatusUser($user, $phone)
    {
        $assign = ContactsAssignment::findOne(['user_id' => $user, 'contact_phone' => $phone]);
        return $assign;
    }

    //thay đổi trạng thái nếu có 2 status pending
    static function pendingStatus($user)
    {
        $assign = ContactsAssignment::find()
            ->where(['user_id' => $user, 'status' => ContactsAssignment::_PENDING])
            ->orderBy(['created_at' => SORT_DESC]);
        if ($assign->count() >= 1) {
            self::changeStatusPending($assign->one());
        }
    }

    static function openCallback($user)
    {
        $now = time();
        $model = ContactsAssignment::find()
            ->where(['user_id' => $user])
            ->andWhere(['is not', 'callback_time', new \yii\db\Expression('null')])
            ->orderBy(['callback_time' => SORT_ASC])->one();
        $data = [];
        if (!$model) return false;
        if (!empty($model->callback_time)) {
            $data = [
                'phone' => $model->contact_phone,
                'time' => Helper::caculateDate($model->updated_at, $model->callback_time, true)
            ];
        }
        if ($now >= $data['time']) {
            $model->callback_time = null;
            $model->status = ContactsAssignment::_PROCESSING;
           return $model->save();
        }
        return false;
    }

    static function isLimitOfDay($user)
    {
        $userPhone = UserModel::findOne($user)->getAttribute("phone_of_day");
        $beginOfDay = strtotime("midnight", time());
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
        $count = ContactsAssignment::find()->where(['user_id' => $user])
            ->orderBy(['created_at' => SORT_ASC])
            ->andFilterWhere([
                'between', 'created_at', $beginOfDay, $endOfDay
            ])->count();
        return $count == $userPhone;
    }

    static function hasCallback($user){
        $model = ContactsAssignment::find()
            ->where(['user_id' => $user])
            ->andWhere(['is not','callback_time' , new \yii\db\Expression('null')])
            ->orderBy(['callback_time' => SORT_ASC])->one();
        if($model){
            return true;
        }else{
            return false;
        }
    }
}