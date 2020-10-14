<?php

use yii\db\Migration;

/**
 * Class m201012_093702_create_table_wearehouse_transaction
 */
class m201012_093702_create_table_wearehouse_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%warehouse_transaction}}', [
            'id' => $this->primaryKey(),
            'warehouse_id' => $this->integer(),
            'quantity' => $this->integer(),
            'note' => $this->string(255)->null(),
            'product_id' => $this->integer()->null(),
            'transaction_type' => $this->string(255)->null(),
            'order_code' => $this->string(255)->null(),
            'status' => $this->smallInteger(6)->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201012_093702_create_table_wearehouse_transaction cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201012_093702_create_table_wearehouse_transaction cannot be reverted.\n";

        return false;
    }
    */
}
