<?php

use yii\db\Migration;

/**
 * Class m200727_034823_add_column
 */
class m200727_034823_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders","vendor_note", $this->string("255")->null());
        $this->addColumn("contacts","code", $this->string("255")->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200727_034823_add_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200727_034823_add_column cannot be reverted.\n";

        return false;
    }
    */
}
