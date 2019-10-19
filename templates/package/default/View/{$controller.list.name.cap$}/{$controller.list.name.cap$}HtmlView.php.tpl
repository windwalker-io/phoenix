<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$};

use Phoenix\Script\BootstrapScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\View\GridView;
use Phoenix\View\ListView;
use Windwalker\Data\Data;

/**
 * The {$controller.list.name.cap$}HtmlView class.
 *
 * @since  1.0
 */
class {$controller.list.name.cap$}HtmlView extends GridView
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = '{$controller.list.name.cap$}';

    /**
     * The fields mapper.
     *
     * @var  array
     */
    protected $fields = [
        'pk' => 'id',
        'title' => 'title',
        'alias' => 'alias',
        'state' => 'state',
        'ordering' => 'ordering',
        'author' => 'created_by',
        'author_name' => 'user_name',
        'created' => 'created',
        'language' => 'language',
        'lang_title' => 'lang_title'
    ];

    /**
     * The grid config.
     *
     * @var  array
     */
    protected $gridConfig = [
        'order_column' => '{$controller.item.name.lower$}.ordering'
    ];

    /**
     * Property simplePagination.
     *
     * @var  boolean
     */
    protected $simplePagination = false;

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = '{$project.name.lower$}.';

    /**
     * prepareData
     *
     * @param \Windwalker\Data\Data                 $data
     *
     * @see ListView
     * ------------------------------------------------------
     * @var  \Windwalker\Structure\Structure        $data ->state
     * @var  \Windwalker\Data\DataSet               $data ->items
     * @var  \Windwalker\Core\Pagination\Pagination $data ->pagination
     * @var  int                                    $data ->total
     * @var  int                                    $data ->limit
     * @var  int                                    $data ->start
     * @var  int                                    $data ->page
     *
     * @see GridView
     * ------------------------------------------------------
     * @var  \Windwalker\Form\Form                  $data ->filterForm
     * @var  \Windwalker\Form\Form                  $data ->batchForm
     * @var  \Windwalker\Core\Widget\Widget         $data ->filterBar
     * @var  boolean                                $data ->showFilterBar
     * @var  \Phoenix\View\Helper\GridHelper        $data ->grid
     *
     * @return  void
     */
    protected function prepareData($data)
    {
        parent::prepareData($data);

        $this->prepareScripts($data);
        $this->prepareMetadata($data);
    }

    /**
     * prepareScripts
     *
     * @param Data $data
     *
     * @return  void
     */
    protected function prepareScripts(Data $data)
    {
        PhoenixScript::core();
        PhoenixScript::grid();
        PhoenixScript::select2('select.has-select2');
        PhoenixScript::multiSelect('#admin-form table', ['duration' => 100]);
        BootstrapScript::checkbox(BootstrapScript::FONTAWESOME);
        BootstrapScript::tooltip();
        PhoenixScript::disableWhenSubmit();
    }

    /**
     * prepareMetadata
     *
     * @param Data $data
     *
     * @return  void
     */
    protected function prepareMetadata(Data $data)
    {
        $this->setTitle();
    }
}
