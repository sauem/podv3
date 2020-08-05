<?php

use yii\db\Migration;

/**
 * Class m200805_085749_change_type_column_customer
 */
class m200805_085749_change_type_column_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("customers","name", $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_085749_change_type_column_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_085749_change_type_column_customer cannot be reverted.\n";

        return false;
    }
    */
}
