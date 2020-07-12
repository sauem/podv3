<?php

use yii\db\Migration;

/**
 * Class m200710_023544_table_orders
 */
class m200710_023544_table_orders extends Migration
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

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'customer_name' => $this->string(255)->notNull(),
            'customer_phone' => $this->string(15)->notNull(),
            'customer_email' => $this->string(100)->null(),
            'address' => $this->string(255),
            'city' => $this->string(255),
            'district' => $this->string(),
            'zipcode' => $this->integer()->null(),
            'country' => $this->string(),
            'sale' => $this->double()->defaultValue(0),
            'sub_total' => $this->double(),
            'total' => $this->double(),
            'order_note' => $this->string()->null(),
            'user_id' => $this->integer(),
            'status' => $this->string(25)->null(),
            'status_note' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("orders_fk_user",
        'orders',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("orders");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_023544_table_orders cannot be reverted.\n";

        return false;
    }
    */
}
