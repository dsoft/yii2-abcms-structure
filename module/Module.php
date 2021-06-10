<?php

namespace abcms\structure\module;

/**
 * structure module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'abcms\structure\module\controllers';

    /**
     * @var string Fully qualified class name of a class that returns the field types.
     * It should implement abcms\structure\classes\FieldTypesInterface
     */
    public $fieldTypesClass = 'abcms\structure\classes\DefaultFieldTypes';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
