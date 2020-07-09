<?php

use yii\db\Migration;

/**
 * Class m200708_220042_table_contacts_log
 */
class m200708_220042_table_contacts_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%contacts_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contact_id' => $this->integer()->notNull(),
            'status' => $this->string(50)->null(),
            'note' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'contacts_log_fk_user',
            'contacts_log',
            'user_id',
            'user',
            'id',
            'cascade');

        $this->addForeignKey(
            'contacts_log_fk_contact',
            'contacts_log',
            'contact_id',
            'contacts',
            'id',
            'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contacts_log}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_220042_table_contacts_log cannot be reverted.\n";

        return false;
    }
    */
}
