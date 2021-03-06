<?php

namespace abcms\structure\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\structure\models\Structure;

class WidgetBase extends Widget
{

    /**
     * Structure model.
     * @var Structure
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
     * @var string
     */
    public $titleTag = 'h2';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if(!$this->model) {
            throw new InvalidConfigException('"model" property must be set.');
        }
        if(!($this->structure && get_class($this->structure) == Structure::className())) {
            throw new InvalidConfigException('"structure" property should be a Structure model.');
        }
        if(!$this->model->hasMethod('returnModelId')) {
            throw new InvalidConfigException('"model" should implement the custom fields behavior: '.\abcms\structure\behaviors\CustomFieldsBehavior::className());
        }
        if($this->structure){
            $this->structure->fillFieldsValues($this->model->returnModelId(), $this->model->id);
        }
        parent::init();
    }

}
