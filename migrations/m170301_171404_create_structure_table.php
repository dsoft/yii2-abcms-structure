<?php

use yii\db\Migration;

/**
 * Handles the creation of table `structure`.
 */
class m170301_171404_create_structure_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('structure', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->null(),
            'modelId' => $this->integer(),
            'pk' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('structure');
    }
}
