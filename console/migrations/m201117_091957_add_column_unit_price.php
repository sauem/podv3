<?php

use yii\db\Migration;

/**
 * Class m201117_091957_add_column_unit_price
 */
class m201117_091957_add_column_unit_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('warehouse_storage', 'unit_price', $this->double(15.2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201117_091957_add_column_unit_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201117_091957_add_column_unit_price cannot be reverted.\n";

        return false;
    }
    */
}
