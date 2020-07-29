<?php

use yii\db\Migration;

/**
 * Class m200728_102842_create_table_image_order
 */
class m200728_102842_create_table_image_order extends Migration
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

        $this->createTable('{{%orders_billing}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'path' => $this->text(),
            'active' => $this->string(50)->defaultValue('draf'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("bill_fk_orders",
            "orders_billing", "order_id", "orders", "id", "cascade");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders_billing}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_102842_create_table_image_order cannot be reverted.\n";

        return false;
    }
    */
}
