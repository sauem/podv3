<?php

use yii\db\Migration;

/**
 * Class m200713_041422_add_column_table
 */
class m200713_041422_add_column_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey("orders_contacts_fk_order","orders_contacts","order_id",
        "orders","id","cascade"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200713_041422_add_column_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200713_041422_add_column_table cannot be reverted.\n";

        return false;
    }
    */
}
