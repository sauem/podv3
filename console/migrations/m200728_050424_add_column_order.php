<?php

use yii\db\Migration;

/**
 * Class m200728_050424_add_column_order
 */
class m200728_050424_add_column_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders","payment_method",$this->integer()->null());
        $this->addColumn("orders","shipping_price",$this->double()->defaultValue(0));
        $this->addColumn("orders","bill_transfer",$this->text()->null());
        $this->addForeignKey("order_fk_payment",
            "orders",
            "payment_method",
        "payment",
        "id",
        "SET NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_050424_add_column_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_050424_add_column_order cannot be reverted.\n";

        return false;
    }
    */
}
