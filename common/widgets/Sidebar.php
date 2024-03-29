<?php

namespace common\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\helpers\Url;

/**
 * Class Sidebar
 * @package common\widgets
 */
class Sidebar extends Menu
{
    public $linkTemplate = '<a href="{url}">{icon} {label}</a>';

    /**
     * @inheritdoc
     */
    public $submenuTemplate = "\n<ul class='treeview-menu'> {items}\n</ul>\n";

    /**
     * @var string Class that will be added for parents "li"
     */
    public $treeClass = 'treeview';

    /**
     * @inheritdoc
     */
    public $activateParents = true;

    /**
     * @inheritdoc
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $class[] = $this->treeClass;
                $menu .= strtr($this->submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }

            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $lines[] = Html::tag($tag, $menu, $options);
        }

        return implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            $replace = !empty($item['icon']) ? [
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{icon}' => '<i class="fa ' . $item['icon'] . '"></i> ',
                '{arrow}' => !empty($item['items']) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : ''
            ] : [
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{icon}' => $item['icon'] !== false ? '<i class="fa fa-link"></i>' : '',
                '{arrow}' => !empty($item['items']) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : ''
            ];

            return strtr($template, $replace);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            $replace = !empty($item['icon']) ? [
                '{label}' => $item['label'],
                '{icon}' => '<i class="fa ' . $item['icon'] . '"></i> ',
                '{arrow}' => !empty($item['items']) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : ''
            ] : [
                '{label}' => $item['label'],
                '{icon}' => $item['icon'] !== false ? '<i class="fa fa-link"></i>' : '',
                '{arrow}' => !empty($item['items']) ? '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>' : ''
            ];

            return strtr($template, $replace);
        }
    }

    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }

        return array_values($items);
    }
}