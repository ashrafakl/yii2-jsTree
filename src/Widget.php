<?php

/**
 * @package   yii2-jsTree
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 * @copyright Copyright &copy; Ashraf Akl, 2014 - 2017
 * @version   1.0.0
 */

namespace ashrafakl\jstree;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget as BaseWidget;

/**
 * Jstree widget is widget wrapper for {@link https://github.com/vakata/jstree} plugin.
 *
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 */
class Widget extends BaseWidget
{

    use WidgetTrait;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initWidget();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->runWidget();
    }

}
