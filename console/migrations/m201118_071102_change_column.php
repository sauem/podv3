<?php

use yii\db\Migration;

/**
 * Class m201118_071102_change_column
 */
class m201118_071102_change_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('warehouse_storage', 'product_id', 'product_sku');
        $this->alterColumn('warehouse_storage', 'product_sku', $this->string(255));

        $this->addForeignKey(
            'storage_fk_warehouse',
            'warehouse_storage',
            'warehouse_id',
            'warehouse',
            'id',
            'CASCADE'
        );

        $this->renameColumn('warehouse_transaction', 'product_id', 'product_sku');
        $this->alterColumn('warehouse_transaction', 'product_sku', $this->string(255));

        $this->addForeignKey(
            'transaction_fk_warehouse',
            'warehouse_transaction',
            'warehouse_id',
            'warehouse',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201118_071102_change_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201118_071102_change_column cannot be reverted.\n";

        return false;
    }
    */
}
