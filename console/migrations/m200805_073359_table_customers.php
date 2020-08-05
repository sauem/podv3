<?php

use yii\db\Migration;

/**
 * Class m200805_073359_table_customers
 */
class m200805_073359_table_customers extends Migration
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

        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->integer(),
            'phone' => $this->string(15)->unique(),
            'email' => $this->string(65)->null(),
            'city' => $this->string(100)->null(),
            'address' => $this->string(255)->null(),
            'district' => $this->string(65)->null(),
            'zipcode' => $this->string(50)->null(),
            'country' => $this->string(65)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_073359_table_customers cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_073359_table_customers cannot be reverted.\n";

        return false;
    }
    */
}
