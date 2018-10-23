<?php

namespace abcms\structure\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\structure\models\Structure;

class WidgetBase extends Widget
{

    /**
     * Structure model or an array that can be used to find the structure model.
     * @var Structure|array 
     */
    public $structure;

    /**
     * Model that the meta attributes belongs to.
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * @var string|null The header title of the widget
     */
    public $title = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if(!$this->model) {
            throw new InvalidConfigException('"model" property must be set.');
        }
        if(!($this->structure && (is_array($this->structure) || get_class($this->structure) == Structure::className()))) {
            throw new InvalidConfigException('"structure" property should be an array or a Structure model.');
        }
        if(is_array($this->structure)) {
            $structure = Structure::findOne($this->structure);
            $this->structure = $structure ? $structure : null;
        }
       if(!$this->model->hasMethod('returnModelId')) {
            throw new InvalidConfigException('"model" should implement the custom fields behavior: '.\abcms\structure\behaviors\CustomFieldsBehavior::className());
        }
        if($structure){
            $this->structure->fillFieldsValues($this->model->returnModelId(), $this->model->id);
        }
        parent::init();
    }

}
