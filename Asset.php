<?php

/**
 * @package   yii2-jsTree
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 * @copyright Copyright &copy; Ashraf Akl, 2014 - 2017
 * @version   1.0.0
 */

namespace ashrafakl\jstree;
use yii\web\AssetBundle;
/**
 * Asset bundle for JsTree
 *
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 */
class Asset extends AssetBundle
{    
    
    public $sourcePath = '@bower/jstree/dist';

    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];    

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $min = YII_DEBUG ? '.min' : '';
//        $this->sourcePath = __DIR__ . DIRECTORY_SEPARATOR . "";
        $this->js[] = "jstree{$min}.js";
        $this->css[] = "themes/default/style{$min}.css";
        parent::init();            
    }  
}