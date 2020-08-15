<?php

use yii\db\Migration;

/**
 * Class m200814_071120_add_table_info_form
 */
class m200814_071120_add_table_info_form extends Migration
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

        $this->createTable('{{%form_info}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'content' => $this->string(255)->unique(),
            'revenue' => $this->double(15.2),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createTable('{{%form_info_sku}}', [
            'id' => $this->primaryKey(),
            'info_id' => $this->integer()->null(),
            'sku' => $this->string(255),
            'qty' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            "fk_form_cat",
            "form_info",
            "category_id",
            "categories",
            "id",
            "SET NULL"
        );

        $this->addForeignKey(
            "info_fk_sku",
            "form_info_sku",
            "info_id",
            "form_info",
            "id",
            "CASCADE"
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200814_071120_add_table_info_form cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200814_071120_add_table_info_form cannot be reverted.\n";

        return false;
    }
    */
}
