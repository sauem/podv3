<?php

use yii\db\Migration;

/**
 * Class m201026_072055_table_transporter
 */
class m201026_072055_table_transporter extends Migration
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

        $this->createTable('{{%archive}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'address' => $this->string(255)->null(),
            'phone' => $this->integer()->null(),
            'logo' => $this->text()->null(),
            'domain' => $this->text()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201026_072055_table_transporter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201026_072055_table_transporter cannot be reverted.\n";

        return false;
    }
    */
}
