<?php

use yii\db\Migration;

/**
 * Class m200708_161001_table_landing_pages
 */
class m200708_161001_table_landing_pages extends Migration
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

        $this->createTable('{{%landing_pages}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'link' => $this->text()->notNull(),
            'category_id' => $this->integer()->null(),
            'product_id' => $this->integer()->null(),
            'user_id' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'landing_fk_product',
            'landing_pages',
            'product_id',
            'products',
            'id',
            'SET NULL');

        $this->addForeignKey(
            'landing_fk_category',
            'landing_pages',
            'category_id',
            'categories',
            'id',
            'SET NULL');

        $this->addForeignKey(
            'landing_fk_user',
            'landing_pages',
            'user_id',
            'user',
            'id',
            'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%landing_pages}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_161001_table_landing_pages cannot be reverted.\n";

        return false;
    }
    */
}
