<?php

use yii\db\Migration;

/**
 * Class m200919_073223_add_column_reason_message
 */
class m200919_073223_add_column_reason_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_log_import","reason_msg", $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200919_073223_add_column_reason_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200919_073223_add_column_reason_message cannot be reverted.\n";

        return false;
    }
    */
}
