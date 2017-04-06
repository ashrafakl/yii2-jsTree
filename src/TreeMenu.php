<?php

/**
 * @package   yii2-jsTree
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 * @copyright Copyright &copy; Ashraf Akl, 2014 - 2017
 * @version   1.0.0
 */

namespace ashrafakl\jstree;

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;

/**
 * TreeMenu widget extends yii\widgets\Menu to add features and drawing JsTree tree list
 *
 * @author    Ashraf Akl <ashrafakl@yahoo.com>
 */
class TreeMenu extends Menu
{


    /**
     * {@inheritDoc}
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if (!empty($item['clicked'])) {                
                $options['data-jstree'] = ['selected' => true];
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

}
