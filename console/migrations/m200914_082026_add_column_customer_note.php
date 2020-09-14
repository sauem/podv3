<?php

use yii\db\Migration;

/**
 * Class m200914_082026_add_column_customer_note
 */
class m200914_082026_add_column_customer_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_log","customer_note", $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200914_082026_add_column_customer_note cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200914_082026_add_column_customer_note cannot be reverted.\n";

        return false;
    }
    */
}
