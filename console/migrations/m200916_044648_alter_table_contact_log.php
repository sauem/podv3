<?php

use yii\db\Migration;

/**
 * Class m200916_044648_alter_table_contact_log
 */
class m200916_044648_alter_table_contact_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("contacts_log_fk_contact", 'contacts_log');
        $this->alterColumn("contacts_log", "contact_id", $this->integer()->null());
        $this->addForeignKey(
            "contacts_log_fk_contact",
            "contacts_log",
            "contact_id",
            "contacts",
            "id",
            "SET NULL"
        );

        $this->addColumn("contacts_log", "contact_code", $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_044648_alter_table_contact_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200916_044648_alter_table_contact_log cannot be reverted.\n";

        return false;
    }
    */
}
