<?php

use yii\db\Migration;

/**
 * Class m200722_023614_table_logs
 */
class m200722_023614_table_logs extends Migration
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

        $this->createTable('{{%logs_import}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'line' => $this->string(255)->null(),
            'message' => $this->string(255),
            'name' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable("{{%logs_import}}");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200722_023614_table_logs cannot be reverted.\n";

        return false;
    }
    */
}
