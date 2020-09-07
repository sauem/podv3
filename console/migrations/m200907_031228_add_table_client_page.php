<?php

use yii\db\Migration;

/**
 * Class m200907_031228_add_table_client_page
 */
class m200907_031228_add_table_client_page extends Migration
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

        $this->createTable('{{%customer_pages}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            "fk_user_client",
            "customer_pages",
            "user_id",
            "user",
            "id",
            "CASCADE"
        );
        $this->addForeignKey(
            "fk_page_client",
            "customer_pages",
            "page_id",
            "landing_pages",
            "id",
            "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200907_031228_add_table_client_page cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200907_031228_add_table_client_page cannot be reverted.\n";

        return false;
    }
    */
}
