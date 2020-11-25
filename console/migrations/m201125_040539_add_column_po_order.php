<?php

use yii\db\Migration;

/**
 * Class m201125_040539_add_column_po_order
 */
class m201125_040539_add_column_po_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('orders_items', 'po', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201125_040539_add_column_po_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201125_040539_add_column_po_order cannot be reverted.\n";

        return false;
    }
    */
}
