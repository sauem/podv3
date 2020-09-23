<?php


namespace backend\jobs;


use backend\models\AuthAssignment;
use backend\models\ContactsAssignment;
use backend\models\ContactsLog;
use backend\models\ContactsModel;
use backend\models\UserModel;
use common\helper\Helper;
use yii\helpers\ArrayHelper;

class doScanContactByCountry
{
    static function _apply($current_user = null)
    {
        // Tiêu chí phân bổ
        // 1. Contacts được chỉ định trực tiếp
        // 2. Số điện thoại phát sinh conctact mới
        // 3. Số điện thoại thuê bao
        // 4. Số điện thoại gọi lại
        // 5. Contact mới số mới

        $phones = ContactsModel::find()->addSelect(['phone', 'country'])->groupBy(['phone', 'country'])->all();
        if (!$phones) {
            return "Không số điện thoại nào được áp dụng";
        }
        $users = AuthAssignment::find()->with('user')
            ->where(['item_name' => UserModel::_SALE])
            ->all();
        $userId = \Yii::$app->user->getId();
        $currentUser = UserModel::findOne($userId);
        if (!Helper::userRole(UserModel::_SALE)) {
            return "Không phải đối tượng phân bổ " . json_encode(\Yii::$app->user->getId());
        }

        // bỏ qua sale đủ số lượng SĐT trong ngày
        if (self::hasLimit($currentUser) || self::hasProcessing($currentUser->id)) {
            // Kiểm tra số điện thoại gọi lại
            self::applyPending($currentUser->id);
            self::rollbackCallback($currentUser);
        } else {
            //chưa có liên hệ nào được phân bổ
            foreach ($phones as $p => $phone) {
                $phoneNumber = $phone->phone;
                $phoneCountry = $phone->country;
                //check số đt k có contacts

                // Emty new contact
                if (self::emptyContact($phoneNumber)) {
                    //Helper::showMessage("Đã hết liên hệ từ số điện thoại $phoneNumber");
                    continue;
                }

                // Bỏ qua SĐT nếu đã được phân bổ
                if (self::exitsPhone($phoneNumber) || self::isLitmitStep($currentUser->id)) {
                    self::checkCompleted($phoneNumber, $currentUser->id);
                    self::applyPending($currentUser->id);
                    self::rollbackCallback($currentUser);
                    continue;
                }
                // Nếu user chưa có số điện thoại trong trạng thái : PROCESSING
                $status = ContactsAssignment::_PROCESSING;
                if (self::hasProcessing($currentUser->id)) {
                    $status = ContactsAssignment::_PENDING;
                }
                // phân bổ số điện thoại cho user
                if ($currentUser->country === $phoneCountry) {
                    self::assignPhoneToUser($phoneNumber, $currentUser->id, $phoneCountry, $status);
                }
            }
            // reset processing pending && callback
            self::applyPending($currentUser->id);
        }
        return "success";
    }

    static function apply($current_user = null)
    {
        // Tiêu chí phân bổ
        // 1. Contacts được chỉ định trực tiếp
        // 2. Số điện thoại phát sinh conctact mới
        // 3. Số điện thoại thuê bao
        // 4. Số điện thoại gọi lại
        // 5. Contact mới số mới

        $phones = ContactsModel::find()->addSelect(['phone', 'country'])->groupBy(['phone', 'country'])->all();
        if (!$phones) {
            return "Không số điện thoại nào được áp dụng";
        }
        $users = AuthAssignment::find()->with('user')
            ->where(['item_name' => UserModel::_SALE])
            ->all();

        foreach ($users as $k => $user) {

            if ($current_user) {
                $currentUser = $current_user;
            } else {
                $currentUser = $user->user[0];
            }

            // $currentUser = $user->user[0];
            // bỏ qua sale đủ số lượng SĐT trong ngày
            if (self::hasLimit($currentUser) || self::hasProcessing($currentUser->id)) {
                // Kiểm tra số điện thoại gọi lại
                self::applyPending($currentUser->id);
                self::rollbackCallback($currentUser);
                continue;
            }
            //chưa có liên hệ nào được phân bổ
            foreach ($phones as $p => $phone) {
                $phoneNumber = $phone->phone;
                $phoneCountry = $phone->country;
                //check số đt k có contacts

                // Emty new contact
                if (self::emptyContact($phoneNumber)) {
                    //Helper::showMessage("Đã hết liên hệ từ số điện thoại $phoneNumber");
                    continue;
                }

                // Bỏ qua SĐT nếu đã được phân bổ
                if (self::exitsPhone($phoneNumber) || self::isLitmitStep($currentUser->id)) {
                    self::checkCompleted($phoneNumber, $currentUser->id);
                    self::applyPending($currentUser->id);
                    self::rollbackCallback($currentUser);
                    continue;
                }
                // Nếu user chưa có số điện thoại trong trạng thái : PROCESSING
                $status = ContactsAssignment::_PROCESSING;
                if (self::hasProcessing($currentUser->id)) {
                    $status = ContactsAssignment::_PENDING;
                }
                // phân bổ số điện thoại cho user
                if ($currentUser->country === $phoneCountry) {
                    self::assignPhoneToUser($phoneNumber, $currentUser->id, $phoneCountry, $status);
                }
            }
            // reset processing pending && callback
            self::applyPending($currentUser->id);
            if ($current_user) {
                break;
            }
        }
        return "success";
    }

    static function removeAssignmentPhone($phone, $user)
    {
        $count = ContactsModel::findAll(['phone' => $phone]);
        if (!$count) {
            $ass = ContactsAssignment::findOne(['contact_phone' => $phone, 'user_id' => $user]);
            if ($ass) {
                $ass->delete();
            }
        }
    }

    static function emptyContact($phone)
    {
        return ContactsModel::hasCompeleted($phone);
    }

    static function isLitmitStep($user_id)
    {
        $limit = 1;
        $model = ContactsAssignment::find()->where(['user_id' => $user_id])
            ->andWhere(['status' => [ContactsAssignment::_PENDING, ContactsAssignment::_PROCESSING]])
            ->andWhere(['is', 'callback_time', new \yii\db\Expression('null')])
            //->andWhere(['<>', 'callback_time', ""])
            ->count();
        if ($model < $limit) {
            return false;
        }
        return true;
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

    static function applyPending($user_id)
    {
        if (!self::hasProcessing($user_id)) {
            $model = ContactsAssignment::find()
                ->where(['user_id' => $user_id, 'status' => ContactsAssignment::_PENDING])
                ->andWhere(['is', 'callback_time', new \yii\db\Expression('null')])
                ->orderBy(['created_at' => SORT_ASC])
                ->one();
            if ($model) {
                $model->status = ContactsAssignment::_PROCESSING;
                return $model->save();
            }
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

    static function rollbackCallback($user)
    {
        $now = time();
        $model = ContactsAssignment::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['is not', 'callback_time', new \yii\db\Expression('null')])
            ->orWhere(['<>', 'callback_time', ""])
            ->andWhere(['status' => ContactsAssignment::_PENDING])
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
                Helper::showMessage("Có liện hệ mới tại số điện thoại $phone");
                // echo "Có liện hệ mới tại số điện thoại $phone";
                return $model->save();
            }
        }
    }

    static function skipPhoneToNewUser($phone)
    {
        $skip = ContactsModel::find()->where([
            'phone' => $phone,
            'status' => ContactsModel::_SKIP,
        ])->all();
        $count = sizeof($skip);
        if ($count >= 3) {
            $assignment = ContactsAssignment::findOne(['contact_phone' => $skip[0]->phone]);
            if ($assignment) {
                $assignment->delete();
            }
        }
    }
}