<?php

namespace abcms\structure\models;

use Yii;
use yii\db\ActiveRecord;
use abcms\library\models\Model;

/**
 * This is the model class for table "structure".
 *
 * @property integer $id
 * @property string $name
 * @property integer $modelId
 * @property integer $pk
 */
class Structure extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modelId', 'pk'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'modelId' => 'Model ID',
            'pk' => 'PK',
        ];
    }

    /**
     * Get Fields models that belongs to this model
     * @return mixed
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['structureId' => 'id']);
    }

    /**
     * Read custom fields from POST and save them for a certain model.
     * @param int $modelId Model class identifier
     * @param int $pk Primary key of the model
     * @param array $data Array where key is the field id and value is the field value.
     */
    public static function saveCustomFields($modelId, $pk, $data)
    {
        if(!is_array($data) || !$data) {
            return;
        }
        foreach($data as $fieldId => $value) {
            $field = Field::find()->with('structure')->andWhere(['id' => (int) $fieldId])->one();
            if($field) {
                $field->commitValue($value, $modelId, $pk);
            }
        }
    }
    
    /**
     * Fill value for each field.
     * @param int $modelId
     * @param int $pk
     */
    public function fillFieldsValues($modelId, $pk){
        $fields = $this->fields;
        foreach($fields as $field){
            $field->fillValue($modelId, $pk);
        }
    }

}
