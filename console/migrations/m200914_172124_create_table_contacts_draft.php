<?php

use yii\db\Migration;

/**
 * Class m200914_172124_create_table_contacts_draft
 */
class m200914_172124_create_table_contacts_draft extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200914_172124_create_table_contacts_draft cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200914_172124_create_table_contacts_draft cannot be reverted.\n";

        return false;
    }
    */
}
