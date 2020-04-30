<?php

namespace abcms\structure\behaviors;

use Yii;
use abcms\library\models\Model;
use abcms\structure\models\Structure;
use abcms\structure\classes\StructureTranslation;
use yii\db\BaseActiveRecord;

class CustomFieldsBehavior extends \yii\base\Behavior
{

    /**
     * @var string the Multilanguage application component ID.
     */
    public $multilanguageId = 'multilanguage';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @inheritdocs
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_VALIDATE => 'validateAutoStructures',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeOwnerSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeOwnerSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'saveAutoStructures',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'saveAutoStructures',
        ];
    }
    
    /**
     * Function called after validating the owner.
     * It validates the structures and structures translation models
     */
    public function validateAutoStructures()
    {
        if($this->autoStructures){
            foreach($this->autoStructures as $structure)
            {
                $model = $structure->getDynamicModel();
                if ($model->load(Yii::$app->request->post())) {
                    $model->isLoaded = true;
                    $model->validate();
                }
            }
        }
        if($this->autoStructuresTranslation){
            foreach($this->autoStructuresTranslation as $structureTranslation)
            {
                $translationModel = $structureTranslation->getTranslationModel();
                if($translationModel->load(Yii::$app->request->post())){
                    $translationModel->isLoaded = true;
                    $translationModel->validate();
                }
            }
        }
    }
    
    /**
     * Function called before saving the owner's data.
     * If the structures or structures translations models have errors, the event will stop the owner insert or update call
     * @param \yii\base\ModelEvent $event
     */
    public function beforeOwnerSave($event)
    {
        if($this->autoStructures){
            foreach($this->autoStructures as $structure){
                $model = $structure->getDynamicModel();
                // Load model if not previously loaded
                if(!$model->isLoaded && $model->load(Yii::$app->request->post())){
                    $model->isLoaded = true;
                }
                if($model->hasErrors()){
                    $event->isValid = false;
                    return;
                }
            }
        }
        if($this->autoStructuresTranslation){
            foreach($this->autoStructuresTranslation as $structureTranslation){
                $translationModel = $structureTranslation->getTranslationModel();
                // Load model if not previously loaded
                if(!$translationModel->isLoaded && $translationModel->load(Yii::$app->request->post())){
                    $translationModel->isLoaded = true;
                }
                if($translationModel->hasErrors()){
                    $event->isValid = false;
                    return;
                }
            }
        }
    }
    
    /**
     * Function called in EVENT_AFTER_INSERT and EVENT_AFTER_UPDATE to save the structures and structures translations data
     */
    public function saveAutoStructures()
    {
        if($this->autoStructures){
            foreach($this->autoStructures as $structure){
                $model = $structure->getDynamicModel();
                $this->saveStructureData($structure->id, $model->attributes);
            }
        }
        if($this->autoStructuresTranslation){
            foreach($this->autoStructuresTranslation as $structureTranslation){
                $translationModel = $structureTranslation->getTranslationModel();
                $structureTranslation->saveTranslationData($translationModel->attributes);
            }
        }
    }
    
    /**
     * @var Structure[]
     */
    protected $autoStructures = [];
    
    /**
     * @var StructureTranslation[]
     */
    protected $autoStructuresTranslation = [];
    
    /**
     * A function to enable automatic saving for certain structures and structures translations
     * @param Structure[]|Structure $structures
     * @param StructureTranslation[]|StructureTranslation $structuresTranslations
     */
    public function enableAutoStructuresSaving($structures, $structuresTranslations = [])
    {
        if(!is_array($structures)){
            $structures = [$structures];
        }
        $this->autoStructures = $structures;
        if(!is_array($structuresTranslations)){
            $structuresTranslations = [$structuresTranslations];
        }
        $this->autoStructuresTranslation = $structuresTranslations;
    }
    
    /**
     * Saves structure data
     */
    public function saveStructureData($structureId, $data)
    {
        $model = $this->owner;
        $modelId = $this->returnModelId();
        $pk = $model->id;
        Structure::saveValuesByName($structureId, $modelId, $pk, $data);
    }

    /**
     * id of the owner model in the model table
     * @var string
     */
    private $_modelId = null;

    /**
     * Return [[_modelId]] and get it from the model table if it's not set.
     * @return string
     */
    public function returnModelId()
    {
        if(!$this->_modelId) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $this->_modelId = Model::returnModelId($owner->className());
        }
        return $this->_modelId;
    }
    
    /**
     * @var array key is the language and value is the custom fields for this language
     */
    private $_customFields = [];
    
    /**
     * Get custom fields of the owner 
     * @param string|null $language
     * @return array
     */
    public function getCustomFields($language = null)
    {
        $customFieldsLanguage = $language ? $language : Yii::$app->sourceLanguage; 
        if(!isset($this->_customFields[$customFieldsLanguage])){
            $model = $this->owner;
            $modelId = $this->returnModelId();
            $pk = $model->id;
            $this->_customFields[$customFieldsLanguage] = Structure::getCustomFields($modelId, $pk, $language, $this->multilanguageId);
        }
        return $this->_customFields[$customFieldsLanguage];
    }
    
    /**
     * Return a specific custom field
     * @param string $field
     * @param string|null $structureName Use it if you want to get the custom field from a certain structure
     * @param string|null $language
     * @return string|null
     */
    public function getCustomField($field, $structureName = null, $language = null)
    {
        $fields = $this->getCustomFields($language);
        if($structureName){
            return isset($fields[$structureName][$field]) ? $fields[$structureName][$field] : null;
        }
        else{
            return isset($fields[$field]) ? $fields[$field] : null;
        }
    }

}
