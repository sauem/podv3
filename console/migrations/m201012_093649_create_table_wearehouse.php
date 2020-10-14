<?php

use yii\db\Migration;

/**
 * Class m201012_093649_create_table_wearehouse
 */
class m201012_093649_create_table_wearehouse extends Migration
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

        $this->createTable('{{%warehouse}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->unique(),
            'country' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'note' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201012_093649_create_table_wearehouse cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201012_093649_create_table_wearehouse cannot be reverted.\n";

        return false;
    }
    */
}
