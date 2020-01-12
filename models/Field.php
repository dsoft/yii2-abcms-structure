<?php

namespace abcms\structure\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\base\DynamicModel;

/**
 * This is the model class for table "structure_field".
 *
 * @property integer $id
 * @property integer $structureId
 * @property string $name
 * @property string $type
 * @property string $label
 * @property string $hint
 * @property integer $isRequired
 * @property string $list list of items used in drop down and checkbox, separated by a line break
 * @property string $additonalData JSON encoded data
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
            [['isRequired', 'ordering'], 'integer'],
            [['name', 'type', 'label', 'hint'], 'string', 'max' => 255],
            [['additionalData', 'list'], 'string'],
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
            'label' => 'Label',
            'hint' => 'Hint',
            'type' => 'Type',
            'isRequired' => 'Is Required',
            'list' => 'List',
            'additionalData' => 'Additional Data',
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
            $label = $this->label ? $this->label : Inflector::camel2words($this->name);
            $list = ($this->list) ? preg_split("/\\r\\n|\\r|\\n/", $this->list) : [];
            $additionalData = json_decode($this->additionalData, true);
            $field = Yii::createObject([
                        'class' => $class,
                        'inputName' => $this->name,
                        'label' => $label,
                        'hint' => $this->hint,
                        'list' => $list,
                        'value' => $this->value,
                        'additionalData' => $additionalData,
                        'isRequired' => $this->isRequired(),
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
     * Return the formatted value
     * Used to display the field value in the Detail View Widget
     */
    public function renderValue()
    {
        $input = $this->getInputObject();
        return $input->renderValue();
    }

    /**
     * Returns the active field
     * @param \yii\widgets\ActiveField $activeField ActiveField Object
     * @return \yii\widgets\ActiveField|string
     */
    public function renderActiveField($activeField)
    {
        $input = $this->getInputObject();
        return $input->renderActiveField($activeField);
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
     * Add custom validation rules to the provided model
     * @param \yii\base\DynamicModel $model
     */
    public function addRulesToModel($model)
    {
        $input = $this->getInputObject();
        return $input->addRulesToModel($model);
    }
    
    /**
     * Return if field is safe for mass assignment
     * @return boolean
     */
    public function isSafe()
    {
        $input = $this->getInputObject();
        return $input->isSafe();
    }
    
    /**
     * Return if field has multiple answers like a multi choice select box
     * @return boolean
     */
    public function hasMultipleAnswers()
    {
        $input = $this->getInputObject();
        return $input->hasMultipleAnswers();
    }
    
    /**
     * Returns if current field is required
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
    }
    
    /**
     * Set value for this class and input object
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $input = $this->getInputObject();
        $input->value = $value;
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
            if(!$this->hasMultipleAnswers()){
                return $this->saveOneAnswer($value, $modelId, $pk);
            }
            else{
                return $this->saveMultipleAnswers($value, $modelId, $pk);
            }
        }
        return false;
    }
    
    /**
     * Save one answer
     * @param string $value
     * @param int $modelId
     * @param int $pk
     * @return boolean
     */
    protected function saveOneAnswer($value, $modelId, $pk)
    {
        $meta = Meta::find()->andWhere(['fieldId' => $this->id, 'modelId' => $modelId, 'pk' => $pk])->one();
        if (!$meta) { // Create new meta if it doesn't exist
            if($value === ''){ // No need to save new meta if answer is empty
                return false;
            }
            $meta = new Meta();
            $meta->fieldId = $this->id;
            $meta->modelId = $modelId;
            $meta->pk = $pk;
        }
        $meta->value = $value;
        return $meta->save(false);
    }    
    
    /**
     * Save multiple answers
     * @param mixed $value
     * @param int $modelId
     * @param int $pk
     * @return boolean
     */
    public function saveMultipleAnswers($value, $modelId, $pk)
    {
        Meta::deleteAll(['modelId' => $modelId, 'pk' => $pk, 'fieldId' => $this->id]);
        $allSaved = true;
        if($value){
            $value = is_array($value) ? $value : [$value];
            foreach($value as $v){
                $meta = new Meta();
                $meta->fieldId = $this->id;
                $meta->modelId = $modelId;
                $meta->pk = $pk;
                $meta->value = $v;
                if(!$meta->save(false)){
                    $allSaved = false;
                }
            }
        }
        return $allSaved;
    }

    /**
     * Fill value for this field
     * @param int $modelId
     * @param int $pk
     * @return boolean
     */
    public function fillValue($modelId, $pk)
    {
        if($this->hasMultipleAnswers()){
            $values = Meta::find()->select('value')->andWhere(['fieldId' => $this->id, 'modelId' => $modelId, 'pk' => $pk])->column();
            $this->setValue($values);
            return true;
        }
        else{
            $meta = Meta::find()->andWhere(['fieldId' => $this->id, 'modelId' => $modelId, 'pk' => $pk])->one();
            if($meta) {
                $this->setValue($meta->value);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Creates a dynamic model for the provided fields and returns it.
     * @param Field[] $fields
     * @return DynamicModel
     */
    public static function getDynamicModel($fields)
    {
        $attributesNames = [];
        $requiredAttributes = [];
        $safeAttributes = [];
        foreach($fields as $field)
        {
            if($field->value){
                $attributesNames[$field->name] = $field->value;
            }
            else{
                $attributesNames[] = $field->name;
            }
            if($field->isSafe()){
                $safeAttributes[] = $field->name;
                if($field->isRequired()){
                    $requiredAttributes[] = $field->name;
                }
            }
        }
        $model = new DynamicModel($attributesNames);
        $model->addRule($safeAttributes, 'safe');
        if($requiredAttributes){
            $model->addRule($requiredAttributes, 'required', ['message' => Yii::t('app', 'This field cannot be left empty')]);
        }
        foreach($fields as $field){
            $field->addRulesToModel($model);
        }
        return $model;
    }

}
