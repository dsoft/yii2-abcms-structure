<?php
namespace abcms\structure\migrations;

use yii\db\Migration;
use abcms\structure\models\Structure;

class m170303_170156_add_seo_fields extends Migration
{

    public function up()
    {
        $this->insert('structure', [
            'name' => 'seo',
        ]);
        $structure = Structure::findOne(['name' => 'seo']);
        if($structure) {
            $this->batchInsert('structure_field', ['structureId', 'name', 'type', 'ordering'], [   
                [$structure->id, 'metaTitle', 'text', 1],
                [$structure->id, 'metaDescription', 'text', 2],
                [$structure->id, 'metaKeywords', 'text', 3],
            ]);
        }
    }

    public function down()
    {
        $structure = Structure::findOne(['name' => 'seo']);
        if($structure) {
            $structureId = $structure->id;
            $this->delete('structure', ['id' => $structureId]);
            $this->delete('structure_field', ['structureId' => $structureId]);
        }
    }

}
