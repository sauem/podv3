<?php

use yii\db\Migration;

/**
 * Class m200805_070923_add_column_country_contacts
 */
class m200805_070923_add_column_country_contacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts","country",$this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_070923_add_column_country_contacts cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_070923_add_column_country_contacts cannot be reverted.\n";

        return false;
    }
    */
}
