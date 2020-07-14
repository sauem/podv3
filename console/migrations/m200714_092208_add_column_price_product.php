<?php

use yii\db\Migration;

/**
 * Class m200714_092208_add_column_price_product
 */
class m200714_092208_add_column_price_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders_items","price",$this->double(15.2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("orders_items","price");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200714_092208_add_column_price_product cannot be reverted.\n";

        return false;
    }
    */
}
