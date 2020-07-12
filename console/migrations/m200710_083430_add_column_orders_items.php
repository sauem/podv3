<?php

use yii\db\Migration;

/**
 * Class m200710_083430_add_column_orders_items
 */
class m200710_083430_add_column_orders_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders_items",'qty',$this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("orders_items","qty");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_083430_add_column_orders_items cannot be reverted.\n";

        return false;
    }
    */
}
