<?php

use yii\db\Migration;

/**
 * Class m200907_032040_remove_foigen_key
 */
class m200907_032040_remove_foigen_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("user_fk_landing", 'user');
        $this->dropColumn("user", "page_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200907_032040_remove_foigen_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200907_032040_remove_foigen_key cannot be reverted.\n";

        return false;
    }
    */
}
