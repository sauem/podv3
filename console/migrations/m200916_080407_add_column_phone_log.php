<?php

use yii\db\Migration;

/**
 * Class m200916_080407_add_column_phone_log
 */
class m200916_080407_add_column_phone_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_log", "phone", $this->string(15)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_080407_add_column_phone_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200916_080407_add_column_phone_log cannot be reverted.\n";

        return false;
    }
    */
}
