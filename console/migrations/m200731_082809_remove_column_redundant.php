<?php

use yii\db\Migration;

/**
 * Class m200731_082809_remove_column_redundant
 */
class m200731_082809_remove_column_redundant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("orders_items","qty");
        $this->dropColumn("orders","bill_transfer");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200731_082809_remove_column_redundant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200731_082809_remove_column_redundant cannot be reverted.\n";

        return false;
    }
    */
}
