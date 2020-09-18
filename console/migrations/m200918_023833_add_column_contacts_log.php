<?php

use yii\db\Migration;

/**
 * Class m200918_023833_add_column_contacts_log
 */
class m200918_023833_add_column_contacts_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_log_import", "name", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "ip", $this->string(50)->null());
        $this->addColumn("contacts_log_import", "link", $this->text()->null());
        $this->addColumn("contacts_log_import", "short_link", $this->text()->null());
        $this->addColumn("contacts_log_import", "utm_source", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "utm_medium", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "utm_content", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "utm_term", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "utm_campaign", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "host", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "hashkey", $this->string(255)->null());
        $this->addColumn("contacts_log_import", "callback_time", $this->integer()->null());
        $this->addColumn("contacts_log_import", "type", $this->string(50)->defaultValue('capture_form'));
        $this->addColumn("contacts_log_import", "register_time", $this->integer()->null());
        $this->addColumn("contacts_log_import", "country", $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200918_023833_add_column_contacts_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200918_023833_add_column_contacts_log cannot be reverted.\n";

        return false;
    }
    */
}
