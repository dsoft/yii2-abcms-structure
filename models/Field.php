<?php

namespace abcms\structure\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * This is the model class for table "structure_field".
 *
 * @property integer $id
 * @property integer $structureId
 * @property string $name
 * @property string $type
 * @property integer $ordering
 */
class Field extends ActiveRecord
{

    /**
     * @var mixed Value of this field
     */
    public $value = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'structure_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'structureId' => 'Structure',
            'name' => 'Name',
            'type' => 'Type',
            'ordering' => 'Ordering',
        ];
    }

    /**
     * Get Structure model that this field belongs to
     * @return mixed
     */
    public function getStructure()
    {
        return $this->hasOne(Structure::className(), ['id' => 'structureId']);
    }

    /**
     * @var \abcms\library\fields\Field
     */
    private $_inputObject = null;

    /**
     * Returns input object
     * @param ActiveRecord $model
     * @return \abcms\library\fields\Field
     */
    protected function getInputObject()
    {
        if(!$this->_inputObject) {
            $class = '\abcms\library\fields\\'.\yii\helpers\Inflector::id2camel($this->type);
            $inputName = "field[$this->id]";
            $label = Inflector::camel2words($this->name);
            $value = $this->value;
            $field = Yii::createObject([
                        'class' => $class,
                        'inputName' => $inputName,
                        'label' => $label,
                        'value' => $value,
            ]);
            $this->_inputObject = $field;
        }
        return $this->_inputObject;
    }

    /**
     * Render the full field
     * @return string
     */
    public function renderField()
    {
        $input = $this->getInputObject();
        return $input->renderField();
    }

    /**
     * Get array that can be used in detail view widget 'attributes' property
     * @return array
     */
    public function getDetailViewAttribute()
    {
        $input = $this->getInputObject();
        return $input->getDetailViewAttribute();
    }
    
    /**
     * Get the value of the input object.
     * @return mixed
     */
    public function getInputValue(){
        $input = $this->getInputObject();
        return $input->value;
    }

    /**
     * Validate input object value
     * @return boolean
     */
    public function validateInput()
    {
        $input = $this->getInputObject();
        return $input->validate();
    }

    /**
     * Check if model is allowed to use this field
     * @param int $modelId Model class identifier
     * @param int $pk Primary key of the model
     * @return boolean
     * @todo Enable structure to be used by classes and specific models.
     */
    public function canSaveForModel($modelId, $pk)
    {
        $structure = $this->structure;
        if($structure->modelId === null && $structure->pk === null) { // If general structure like 'seo'
            return true;
        }
        if($structure->modelId === $modelId && $structure->pk === null){ // If class specific structure
            return true;
        }
        if($structure->modelId === $modelId && $structure->pk === $pk){ // If model specific structure
            return true;
        }
        return false;
    }

    /**
     * Save or update value of this field for the provided model
     * @param mixed $value
     * @param int $modelId Model class identifier
     * @param int $pk Primary key of the model
     * @return boolean
     */
    public function commitValue($value, $modelId, $pk)
    {
        if($this->canSaveForModel($modelId, $pk)) {
            $alreadyAvailable = true;
            $meta = Meta::find()->andWhere(['fieldId' => $this->id, 'modelId' => $modelId, 'pk' => $pk])->one();
            if(!$meta) { // Create new meta if it doesn't exist
                $alreadyAvailable = false;
                $meta = new Meta();
                $meta->fieldId = $this->id;
                $meta->modelId = $modelId;
                $meta->pk = $pk;
            }
            $this->value = $value;
            if($this->validateInput()) {
                $meta->value = $this->getInputValue();
                if(!$alreadyAvailable && !$meta->value){ // No need to create new meta if value is empty
                    return false;
                }
                return $meta->save(false);
            }
        }
        return false;
    }

    /**
     * Fill value for this field
     * @param int $modelId
     * @param int $pk
     * @return boolean
     */
    public function fillValue($modelId, $pk)
    {
        $meta = Meta::find()->andWhere(['fieldId' => $this->id, 'modelId' => $modelId, 'pk' => $pk])->one();
        if($meta) {
            $this->value = $meta->value;
            return true;
        }
        return false;
    }

}
