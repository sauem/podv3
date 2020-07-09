<?php

use yii\db\Migration;

/**
 * Class m200708_215316_table_contacts
 */
class m200708_215316_table_contacts extends Migration
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

        $this->createTable('{{%contacts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'phone' => $this->string(15)->notNull(),
            'email' => $this->string(100)->null(),
            'address' => $this->text()->null(),
            'zipcode' => $this->integer()->null(),
            'option' => $this->text()->null(),
            'ip' => $this->string(50)->null(),
            'note' => $this->string(255)->null(),
            'link' => $this->text()->null(),
            'short_link' => $this->text()->null(),
            'utm_source' => $this->string(255)->null(),
            'utm_medium' => $this->string(255)->null(),
            'utm_content' => $this->string(255)->null(),
            'utm_term' => $this->string(255)->null(),
            'utm_campaign' => $this->string(255)->null(),
            'host' => $this->string(255)->null(),
            'hashkey' => $this->string(255)->null(),
            'status' => $this->string(50)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contacts}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_215316_table_contacts cannot be reverted.\n";

        return false;
    }
    */
}
