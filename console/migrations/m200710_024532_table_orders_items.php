<?php

use yii\db\Migration;

/**
 * Class m200710_024532_table_orders_items
 */
class m200710_024532_table_orders_items extends Migration
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

        $this->createTable('{{%orders_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_sku' => $this->string(255),
            'product_option' => $this->string()->null(),
            'contact_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey("orderitem_fk_order",
        "orders_items",
            "order_id",
            "orders",
            "id",
            "cascade"
        );
        $this->addForeignKey("orderitem_fk_products",
            "orders_items",
            "product_sku",
            "products",
            "sku",
            "cascade"
        );
        $this->addForeignKey("orderitem_fk_contact",
            "orders_items",
            "contact_id",
            "contacts",
            "id",
            "cascade"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200710_024532_table_orders_items cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_024532_table_orders_items cannot be reverted.\n";

        return false;
    }
    */
}
