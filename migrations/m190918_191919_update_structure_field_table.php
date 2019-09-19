<?php
namespace abcms\structure\migrations;

use yii\db\Migration;

/**
 * Class m190918_191919_update_structure_field_table
 */
class m190918_191919_update_structure_field_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('structure_field', 'isRequired', $this->boolean()->notNull()->defaultValue(0)->after('type'));
        $this->addColumn('structure_field', 'additionalData', $this->text()->null()->after('isRequired'));
        $this->addColumn('structure_field', 'list', $this->text()->null()->after('isRequired'));
        $this->addColumn('structure_field', 'hint', $this->string()->null()->after('type'));
        $this->addColumn('structure_field', 'label', $this->string()->null()->after('type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('structure_field', 'isRequired');
        $this->dropColumn('structure_field', 'additionalData');
        $this->dropColumn('structure_field', 'list');
        $this->dropColumn('structure_field', 'hint');
        $this->dropColumn('structure_field', 'label');
    }
}
