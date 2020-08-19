<?php

use yii\db\Migration;

/**
 * Class m200819_034430_change_column
 */
class m200819_034430_change_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("form_info","content", $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200819_034430_change_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_034430_change_column cannot be reverted.\n";

        return false;
    }
    */
}
