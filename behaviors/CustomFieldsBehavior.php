<?php

namespace abcms\structure\behaviors;

use Yii;
use abcms\library\models\Model;
use abcms\structure\models\Structure;

class CustomFieldsBehavior extends \yii\base\Behavior
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Saves custom fields of the owner from POST
     */
    public function saveCustomFields()
    {
        $model = $this->owner;
        $modelId = $this->returnModelId();
        $pk = $model->id;
        Structure::saveCustomFields($modelId, $pk, Yii::$app->request->post('field'));
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

}
