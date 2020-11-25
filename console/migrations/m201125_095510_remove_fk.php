<?php

use yii\db\Migration;

/**
 * Class m201125_095510_remove_fk
 */
class m201125_095510_remove_fk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('warehouse_transaction', 'po_code', $this->string(255)->null());

        $this->dropForeignKey('transaction_fk_warehouse', 'warehouse_transaction');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201125_095510_remove_fk cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201125_095510_remove_fk cannot be reverted.\n";

        return false;
    }
    */
}
