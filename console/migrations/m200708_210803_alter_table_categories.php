<?php

use yii\db\Migration;

/**
 * Class m200708_210803_alter_table_categories
 */
class m200708_210803_alter_table_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->alterColumn("categories","description",$this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200708_210803_alter_table_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_210803_alter_table_categories cannot be reverted.\n";

        return false;
    }
    */
}
