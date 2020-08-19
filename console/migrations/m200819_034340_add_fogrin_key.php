<?php

use yii\db\Migration;

/**
 * Class m200819_034340_add_fogrin_key
 */
class m200819_034340_add_fogrin_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            "fk_form_cat",
            "form_info",
            "category_id",
            "categories",
            "id",
            "SET NULL"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200819_034340_add_fogrin_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200819_034340_add_fogrin_key cannot be reverted.\n";

        return false;
    }
    */
}
