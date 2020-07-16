<?php

use yii\db\Migration;

/**
 * Class m200716_093910_add_column_phone_assign_user
 */
class m200716_093910_add_column_phone_assign_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("user","phone_of_day",$this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("user","phone_of_day");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200716_093910_add_column_phone_assign_user cannot be reverted.\n";

        return false;
    }
    */
}
