<?php

/**
 * @package   yii2-jsTree
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 * @copyright Copyright &copy; Ashraf Akl, 2014 - 2017
 * @version   1.0.0
 */

namespace ashrafakl\jstree;

use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii;

/**
 * 
 * WidgetTrait is the trait, which provides basic for JsTree features
 *
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 */
trait WidgetTrait
{

    /**
     * @var array jstree plugin options.
     * @link https://www.jstree.com/api/
     */
    public $pluginOptions = [];

    /**
     * @var array jstree events. You must define events in `event-name => event-function` format. For example:
     *
     * ~~~
     * pluginEvents = [
     *     'loaded.jstree' => 'function(event, data) { log("data"); }',
     * ];
     * ~~~
     */
    public $pluginEvents = [];

    /**
     *
     * @var string root css icon
     */
    public $rootIcon = 'glyphicon glyphicon-folder-close';

    /**
     *
     * @var string default css icon
     */
    public $defautIcon = 'glyphicon glyphicon-folder-close';

    /**
     *
     * @var tree list to be rendering inside tree container , this option is not allowed if  [[ajaxDatUrl]] is not null
     */
    public $treeList;

    /**
     *
     * @var string|array ajax data url  
     */
    public $ajaxDataUrl;

    /**
     *
     * @var string|array lazyload callback
     */
    public $lazyLoad;
    
    /**
     *
     * @var bool use multiple selection or not
     */
    public $multiple = false;

    /**
     * Register JsTtree needed assets
     */
    public function registerAssets()
    {
        Asset::register($this->getView());
    }

    /**
     * Register JsTtree plugin
     */
    public function registerPlugin()
    {
        $this->registerAssets();
        if ($defaultOptions = $this->setDefaultOptions()) {
            $this->pluginOptions = ArrayHelper::merge($defaultOptions, $this->pluginOptions);
        }
        $options = $this->pluginOptions ? Json::encode($this->pluginOptions) : '';
        $js = "$('#{$this->id}').jstree({$options});";  //"$.jstree.defaults.checkbox.cascade = 'down+undetermined";
        $this->registerPluginEvents();
        $this->getView()->registerJs($js);
    }

    /**
     * Set default options
     * @return array
     */
    protected function setDefaultOptions()
    {
        return [
            'core' => [
                'themes' => [
                    'reponsive' => true,
                    'stripes' => true,
                    'dots' => false,
                    'ellipsis' => false,
                ]
            ],
            'types' => [
                'root' => [
                    'icon' => $this->rootIcon,
                ],
                'default' => [
                    'icon' => $this->defautIcon,
                ]
            ],
            'plugins' => ['types', 'wholerow'],
        ];
    }

    /**
     * Render tree list
     * @see yii\widgets\Menu::items for details on list of menu items structure
     * @return string
     */
    protected function renderTreelist()
    {
        if ($this->treeList && isset($this->pluginOptions['core']['data']['url'])) {
            throw new InvalidConfigException('You cannot use treeList along with jstree core data url plugin option.');
        }
        return TreeMenu::widget(['items' => $this->treeList]);
    }

    /**
     * Initializes the widget.
     */
    protected function initWidget()
    {
        $this->options['id'] = $this->id;
        if (empty($this->options['class'])) {
            $this->options['class'] = "panel panel-default";
        }
        if ($this->ajaxDataUrl) {
            $this->pluginOptions['core']['data']['url'] = \yii\helpers\Url::to($this->ajaxDataUrl);
        }
        if ($this->lazyLoad) {
            if (is_string($this->lazyLoad)) {
                $this->pluginOptions['core']['data']['data'] = new JsExpression($this->lazyLoad);
            } else if (is_array($this->lazyLoad)) {
                $return = [];
                foreach ($this->lazyLoad as $key=>$val){
                    $return[] ="'{$key}':{$val}";
                }
                $return = implode(',', $return);
                $this->pluginOptions['core']['data']['data'] = new JsExpression("function(node) {
                        return {{$return}};
                }");
            }
        }
        
        $this->pluginOptions['core']['multiple'] = $this->multiple;
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Executes the widget.
     * @param string $beforeTree content to be apended before rendered the tree
     */
    protected function runWidget($beforeTree = null)
    {        
        $content = ob_get_clean();
        echo $beforeTree;
        echo Html::beginTag('div', $this->options);        
        echo $content;
        if ($this->treeList) {
            echo $this->renderTreelist();
        }        
        echo Html::endTag("div");        
        $this->registerPlugin();
    }

    /**
     * Registers JsTree events handlers
     */
    protected function registerPluginEvents()
    {
        if (!empty($this->pluginEvents)) {
            $js = [];
            foreach ($this->pluginEvents as $event => $handler) {
                if (is_string($handler)) {
                    $handler = new JsExpression($handler);
                }
                $js[] = "jQuery('#{$this->id}').on('{$event}', {$handler});";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }

}
