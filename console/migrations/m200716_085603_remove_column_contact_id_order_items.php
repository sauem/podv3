<?php

use yii\db\Migration;

/**
 * Class m200716_085603_remove_column_contact_id_order_items
 */
class m200716_085603_remove_column_contact_id_order_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("orderitem_fk_contact","orders_items");
        $this->dropColumn("orders_items","contact_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200716_085603_remove_column_contact_id_order_items cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200716_085603_remove_column_contact_id_order_items cannot be reverted.\n";

        return false;
    }
    */
}
