<?php

use yii\db\Migration;

/**
 * Class m200915_093214_alter_column_note_empty
 */
class m200915_093214_alter_column_note_empty extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts_log", "note", $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200915_093214_alter_column_note_empty cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200915_093214_alter_column_note_empty cannot be reverted.\n";

        return false;
    }
    */
}
