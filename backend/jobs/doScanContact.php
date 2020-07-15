<?php
namespace backend\jobs;
use backend\models\AuthAssignment;
use backend\models\ContactsAssignment;
use backend\models\ContactsModel;
use backend\models\UserModel;
use yii\helpers\ArrayHelper;

class doScanContact {
    public static function apply()
    {
        $phones = ContactsModel::find()->addSelect(['phone'])->distinct()->asArray()->all();
        $phones = ArrayHelper::getColumn($phones, 'phone');
        $users = AuthAssignment::find()->with('user')->where(['item_name' => UserModel::_SALE])->asArray()->all();
        $users = ArrayHelper::getColumn($users, 'user_id');

        foreach ($users as $user) {
            $count = self::countAssignUser($user);
            if ($count >= 2) {
                self::pendingStatus($user);
                continue;
            } else {
                foreach ($phones as $k => $phone) {
                    $exitStatus = self::getStatusUser($user, $phone);
                    if ($exitStatus) {
                        self::changeStatusPending($exitStatus);
                        continue;
                    } else {
                        if (!self::phoneExitsts($phone) && self::countAssignUser($user) < 2) {
                            $count = self::countAssignUser($user);
                            switch ($count) {
                                case 1:
                                    self::assignUser($phone, $user, ContactsAssignment::_PENDING);
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

    static function changeStatusPending(ContactsAssignment $exitStatus)
    {
        if ($exitStatus) {
            $exitStatus->status = ContactsAssignment::_PROCESSING;
            $exitStatus->save();
        }
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
    }

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

    static function pendingStatus($user)
    {
        $assign = ContactsAssignment::find()
            ->where(['user_id' => $user, 'status' => ContactsAssignment::_PENDING])
            ->orderBy(['created_at' => SORT_ASC]);
        if ($assign->count() > 1) {
            self::changeStatusPending($assign->all()[0]);
        }
    }
}