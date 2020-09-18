<?php

use yii\db\Migration;

/**
 * Class m200918_173842_add_column_contact_wait
 */
class m200918_173842_add_column_contact_wait extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_log_import","reason",$this->string(255)->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200918_173842_add_column_contact_wait cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200918_173842_add_column_contact_wait cannot be reverted.\n";

        return false;
    }
    */
}
