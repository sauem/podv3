<?php

use yii\db\Migration;

/**
 * Class m200730_061443_add_column_contact_time
 */
class m200730_061443_add_column_contact_time extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("contacts","register_time", $this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200730_061443_add_column_contact_time cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200730_061443_add_column_contact_time cannot be reverted.\n";

        return false;
    }
    */
}
