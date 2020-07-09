<?php

use yii\db\Migration;

/**
 * Class m200708_215854_table_contacts_assignment
 */
class m200708_215854_table_contacts_assignment extends Migration
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

        $this->createTable('{{%contacts_assignment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contact_phone' => $this->string(15)->notNull(),
            'status' => $this->string(50)->null(),
            'callback_time' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'contacts_assignment_fk_user',
            'contacts_assignment',
            'user_id',
            'user',
            'id',
            'cascade');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contacts_assignment}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_215854_table_contacts_assignment cannot be reverted.\n";

        return false;
    }
    */
}
