<?php

use yii\db\Migration;

/**
 * Class m200708_155750_table_products
 */
class m200708_155750_table_products extends Migration
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

        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sku' => $this->string(255)->unique()->notNull(),
            'regular_price' => $this->double(15.2)->defaultValue(0),
            'sale_price' => $this->double(15.2)->defaultValue(0),
            'category_id' => $this->integer(),
            'description' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'product_fk_category',
            'products',
            'category_id',
            'categories',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_fk_category','products');
        $this->dropTable('{{%products}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }
*/
//    public function down()
//    {
//        $this->dropForeignKey('product_fk_category','products');
//        $this->dropTable('{{%products}}');
//    }

}
