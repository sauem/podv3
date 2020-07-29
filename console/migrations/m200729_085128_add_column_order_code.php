<?php

use yii\db\Migration;

/**
 * Class m200729_085128_add_column_order_code
 */
class m200729_085128_add_column_order_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders","block_time", $this->integer()->defaultValue(0));
        $this->addColumn("orders","admin_block", $this->integer()->null());
        $this->addColumn("contacts","type", $this->string(50)->defaultValue("capture_form"));
        $this->addForeignKey("admin_block_order", "orders","admin_block","user","id","SET NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200729_085128_add_column_order_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200729_085128_add_column_order_code cannot be reverted.\n";

        return false;
    }
    */
}
