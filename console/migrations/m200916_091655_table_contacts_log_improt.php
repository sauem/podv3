<?php

use yii\db\Migration;

/**
 * Class m200916_091655_table_contacts_log_improt
 */
class m200916_091655_table_contacts_log_improt extends Migration
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

        $this->createTable('{{%contacts_log_import}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->integer()->notNull(),
            'code' => $this->integer()->notNull(),
            'address' => $this->string(50)->null(),
            'zipcode' => $this->string(255)->null(),
            'category' => $this->string(255)->null(),
            'option' => $this->string(255)->null(),
            'customer_note' => $this->string(255)->null(),
            'status' => $this->string(50)->notNull(),
            'note' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200916_091655_table_contacts_log_improt cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200916_091655_table_contacts_log_improt cannot be reverted.\n";

        return false;
    }
    */
}
