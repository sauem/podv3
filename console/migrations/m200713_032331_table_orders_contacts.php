<?php

use yii\db\Migration;

/**
 * Class m200713_032331_table_orders_contacts
 */
class m200713_032331_table_orders_contacts extends Migration
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

        $this->createTable('{{%orders_contacts}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'contact_id' => $this->integer(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey("orders_fk_cotactid",
            'orders_contacts',
            'contact_id',
            'contacts',
            'id',
            'cascade'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%orders_contacts}}");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200713_032331_table_orders_contacts cannot be reverted.\n";

        return false;
    }
    */
}
