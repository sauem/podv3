<?php

use yii\db\Migration;

/**
 * Class m200710_083439_add_column_products
 */
class m200710_083439_add_column_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("products","option",$this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("products","option");

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_083439_add_column_products cannot be reverted.\n";

        return false;
    }
    */
}
