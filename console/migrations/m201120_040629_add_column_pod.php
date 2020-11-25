<?php

use yii\db\Migration;

/**
 * Class m201120_040629_add_column_pod
 */
class m201120_040629_add_column_pod extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('warehouse_storage', 'po_code', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201120_040629_add_column_pod cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201120_040629_add_column_pod cannot be reverted.\n";

        return false;
    }
    */
}
