<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Phoenix\Record;

use Windwalker\Console\Exception\WrongArgumentException;
use Windwalker\Console\Prompter\BooleanPrompter;
use Windwalker\Core\Console\ConsoleHelper;
use Windwalker\Core\Console\CoreCommand;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Ioc;
use Windwalker\Edge\Edge;
use Windwalker\Edge\Loader\EdgeStringLoader;
use Windwalker\Filesystem\File;
use Windwalker\Record\Record;
use Windwalker\String\StringNormalise;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The SyncCommand class.
 *
 * @since  1.1
 */
class SyncCommand extends CoreCommand
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'sync';

    /**
     * Property usage.
     *
     * @var  string
     */
    protected $usage = '%s <package> <record_name> [<table>] [options]';

    /**
     * Property description.
     *
     * @var  string
     */
    protected $description = 'Sync tables columns to RecordTrait to support auto-complete.';

    /**
     * init
     *
     * @return  void
     */
    protected function init()
    {
    }

    /**
     * Execute this command.
     *
     * @return int
     *
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     * @throws \Windwalker\Edge\Exception\EdgeException
     * @since  2.0
     */
    protected function doExecute()
    {
        $package = $this->getArgument(0);

        if (!$package) {
            throw new WrongArgumentException('Please enter package name.');
        }

        $package = ConsoleHelper::getAllPackagesResolver()->getPackage($package);

        if (!$package) {
            throw new \InvalidArgumentException(sprintf('Package: %s not found', $this->getArgument(0)));
        }

        $recordClass = $this->getArgument(1);

        if (!$recordClass) {
            throw new WrongArgumentException('Please enter record name or class.');
        }

        $recordClass  = StringNormalise::toClassNamespace($recordClass);
        $pkgNamespace = ReflectionHelper::getNamespaceName($package);

        if (!class_exists($recordClass)) {
            $recordClass = $pkgNamespace . '\\Record\\' . ucfirst($recordClass) . 'Record';
        }

        $table = null;

        if (class_exists($recordClass)) {
            try {
                /** @var Record $record */
                $record = new $recordClass();
                $table  = $record->getTableName();
            } catch (\Exception $e) {
                // Nothing
            }
        }

        if ($this->getArgument(2)) {
            $table = $this->getArgument(2);
        }

        $columns  = Ioc::getDatabase()->getTable($table, true)->getColumnDetails(true);
        $fields   = [];
        $dataType = Ioc::getDatabase()->getTable($table)->getDataType();

        foreach ($columns as $column) {
            $fields[] = [
                'name' => $column->Field,
                'type' => $dataType::getPhpType(explode('(', $column->Type)[0]),
            ];
        }

        // Prepare interface name
        $classParts = explode('\\', $recordClass);
        $name      = end($classParts);
        $name      = str_replace('Record', '', $name);
        $shortName = ucfirst($name) . 'DataInterface';

        $data = [
            'package_namespace' => $pkgNamespace . '\\Record\\Columns',
            'short_name' => $shortName,
            'columns' => $fields,
            'name' => strtolower($name),
            'package_name' => $package->getName()
        ];

        $content = (new Edge(new EdgeStringLoader()))->render($this->getTemplate(), $data);

        $file = $package->getDir() . '/Record/Columns/' . $shortName . '.php';

        if (is_file($file) && !(new BooleanPrompter())
                ->ask('File: <comment>' . $file . '</comment> exists, do you want to override it? [N/y]: ', false)) {
            throw new \RuntimeException('  Canceled...');
        }

        File::write($file, $content);

        $this->out()->out('Writing file: <info>' . $file . '</info> success.');

        return true;
    }

    /**
     * getTemplate
     *
     * @return  string
     */
    protected function getTemplate()
    {
        $year = Chronos::create()->year;

        return <<<TMPL
{!! '<' . '?php' !!}
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) {$year} LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {{ \$package_namespace }};

/**
 * The {{ \$short_name }} class.
 *
 * Run
 * ```
 * php windwalker phoenix record sync {{ \$package_name }} {{ \$name }}
 * ```
 * to update fields.
 *
@foreach (\$columns as \$column)
 * @property  {{ \$column['type'] }}  {{ \$column['name'] }}
@endforeach
 */
interface {{ \$short_name }}
{

}

TMPL;
    }
}
