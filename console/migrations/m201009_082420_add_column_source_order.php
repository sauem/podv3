<?php

use yii\db\Migration;

/**
 * Class m201009_082420_add_column_source_order
 */
class m201009_082420_add_column_source_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders", 'source_order', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201009_082420_add_column_source_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201009_082420_add_column_source_order cannot be reverted.\n";

        return false;
    }
    */
}
