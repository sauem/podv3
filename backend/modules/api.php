<?php

namespace backend\modules;

/**
 * api module definition class
 */
class api extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::configure($this, require(__DIR__ . '/config/config.php'));
    }
}
