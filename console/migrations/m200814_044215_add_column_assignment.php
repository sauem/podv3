<?php

use yii\db\Migration;

/**
 * Class m200814_044215_add_column_assignment
 */
class m200814_044215_add_column_assignment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts_assignment", "country", $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200814_044215_add_column_assignment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200814_044215_add_column_assignment cannot be reverted.\n";

        return false;
    }
    */
}
