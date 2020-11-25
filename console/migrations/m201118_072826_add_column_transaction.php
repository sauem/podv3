<?php

use yii\db\Migration;

/**
 * Class m201118_072826_add_column_transaction
 */
class m201118_072826_add_column_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('warehouse_transaction', 'total_average', $this->double(15.2)->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201118_072826_add_column_transaction cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201118_072826_add_column_transaction cannot be reverted.\n";

        return false;
    }
    */
}
