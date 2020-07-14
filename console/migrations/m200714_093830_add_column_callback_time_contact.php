<?php

use yii\db\Migration;

/**
 * Class m200714_093830_add_column_callback_time_contact
 */
class m200714_093830_add_column_callback_time_contact extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts","callback_time",$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("contacts","callback_time");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200714_093830_add_column_callback_time_contact cannot be reverted.\n";

        return false;
    }
    */
}
