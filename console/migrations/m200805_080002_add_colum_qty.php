<?php

use yii\db\Migration;

/**
 * Class m200805_080002_add_colum_qty
 */
class m200805_080002_add_colum_qty extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("orders_items","qty", $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_080002_add_colum_qty cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200805_080002_add_colum_qty cannot be reverted.\n";

        return false;
    }
    */
}
