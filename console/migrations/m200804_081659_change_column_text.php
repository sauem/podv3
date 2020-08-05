<?php

use yii\db\Migration;

/**
 * Class m200804_081659_change_column_text
 */
class m200804_081659_change_column_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts", "note", $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200804_081659_change_column_text cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200804_081659_change_column_text cannot be reverted.\n";

        return false;
    }
    */
}
