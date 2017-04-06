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
use yii\widgets\InputWidget as BaseInputWidget;
use \yii\helpers\ArrayHelper;

/**
 * Jstree widget is widget wrapper for {@link https://github.com/vakata/jstree} plugin.
 *
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 */
class InputWidget extends BaseInputWidget
{

    use WidgetTrait;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initWidget();
        if (!$this->hasModel()) {
            echo Html::hiddenInput($this->name, $this->value, ['id' => "{$this->id}-input"]);
        } else {
            if (empty($this->pluginOptions['core']['multiple'])) {
                echo Html::activeHiddenInput($this->model, $this->attribute, ['id']);
            } else {
                echo Html::activeListBox($this->model, $this->attribute, [], ['class' => 'hidden', 'multiple' => 'multiple']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->hasModel()) {
            $inputId = Html::getInputId($this->model, $this->attribute);
            $value =  Json::encode($this->model->{$this->attribute});
        } else {
            $inputId = "{$this->id}-input";
            $value = Json::encode($this->value);
        }
        $this->pluginEvents['loaded.jstree'] = "function(event, data){                
                if({$value}){
                    data.instance.select_node({$value});  
                }
            }";
        if (empty($this->pluginOptions['core']['multiple'])) {

            $this->pluginEvents['changed.jstree'] = "function(event, data, x){
                if(data.selected){
                    $('#{$inputId}').val(data.selected[0]);
                }
            }";
        } else {            
            $this->pluginEvents['changed.jstree'] = "function(event, data, x){                                                
                if(!selected && data.selected){
                    var selected = '';                    
                    for(var i in data.selected){
                        selected += '<option value=\"' + data.selected[i] +'\" selected=\"selected\">' + data.selected[i] +'</option>';
                    }       
                    $('#{$inputId}').html(selected);
                    console.log($('#{$inputId}').val());    
                }
            }";
        }
        $this->runWidget();
    }

}
