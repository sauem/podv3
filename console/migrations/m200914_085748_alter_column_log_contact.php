<?php

use yii\db\Migration;

/**
 * Class m200914_085748_alter_column_log_contact
 */
class m200914_085748_alter_column_log_contact extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("contacts_log_fk_user", "contacts_log");
        $this->alterColumn("contacts_log", "user_id", $this->integer()->null());
        $this->addForeignKey(
            "contacts_log_fk_user",
            "contacts_log",
            "user_id",
            "user",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200914_085748_alter_column_log_contact cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200914_085748_alter_column_log_contact cannot be reverted.\n";

        return false;
    }
    */
}
