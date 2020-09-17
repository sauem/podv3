<?php

use yii\db\Migration;

/**
 * Class m200917_082010_alter_column_name_contacts
 */
class m200917_082010_alter_column_name_contacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("contacts","name",$this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200917_082010_alter_column_name_contacts cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200917_082010_alter_column_name_contacts cannot be reverted.\n";

        return false;
    }
    */
}
