<?php

use yii\db\Migration;

/**
 * Class m200811_061338_add_column_order_code
 */
class m200811_061338_add_column_order_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders","code",$this->string(50)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200811_061338_add_column_order_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200811_061338_add_column_order_code cannot be reverted.\n";

        return false;
    }
    */
}
