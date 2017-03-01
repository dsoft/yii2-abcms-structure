<?php

use yii\db\Migration;

/**
 * Handles the creation of table `meta`.
 */
class m170301_171448_create_structure_field_meta_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('structure_field_meta', [
            'id' => $this->primaryKey(),
            'fieldId' => $this->integer()->notNull(),
            'modelId' => $this->integer(),
            'pk' => $this->integer(),
            'value' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('structure_field_meta');
    }
}
