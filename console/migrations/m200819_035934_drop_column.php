<?php

use yii\db\Migration;

/**
 * Class m200819_035934_drop_column
 */
class m200819_035934_drop_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn("form_info", "content");
        $this->addColumn("form_info", "content", $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200819_035934_drop_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_035934_drop_column cannot be reverted.\n";

        return false;
    }
    */
}
