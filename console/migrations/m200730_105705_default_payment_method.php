<?php

use yii\db\Migration;

/**
 * Class m200730_105705_default_payment_method
 */
class m200730_105705_default_payment_method extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert("payment", [
            "id" => 9999,
            "name" => "Chuyển khoản",
            "description" => "Hình thức thanh toán chuyển khoản ngân hàng",
            "created_at" => time(),
            "updated_at" => time()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200730_105705_default_payment_method cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200730_105705_default_payment_method cannot be reverted.\n";

        return false;
    }
    */
}
