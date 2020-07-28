<?php

use yii\db\Migration;

/**
 * Class m200728_050049_create_payment_info
 */
class m200728_050049_create_payment_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_info}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer(),
            'bank_account' => $this->string(255),
            'bank_name' => $this->string(255),
            'bank_number' => $this->string(255),
            'bank_address' => $this->string(255),
            'bank_description' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("info_fk_payment",
            "payment_info",
            "payment_id",
            "payment",
            "id",
            "cascade"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("payment_info");
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_050049_create_payment_info cannot be reverted.\n";

        return false;
    }
    */
}
