<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Toolbar;

use Windwalker\Core\Router\Router;

/**
 * The Toolbar class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Toolbar2
{
	/**
	 * add
	 *
	 * @param string $name
	 * @param string $route
	 * @param array  $queries
	 *
	 * @return  string
	 */
	public static function add($name, $route = 'admin:new', $queries = [], $class = '')
	{
		$queries['name'] = $name;

		$url = Router::buildHtml($route, $queries);

		return <<<HTML
<a class="btn btn-success $class" href="$url">
	<span class="glyphicon glyphicon-plus"></span> New
</a>
HTML;
	}

	/**
	 * cancel
	 *
	 * @param string $route
	 * @param array  $queries
	 * @param string $class
	 *
	 * @return string
	 */
	public static function cancel($route, $queries = [], $class = '')
	{
		$url = Router::buildHtml($route, $queries);

		return <<<HTML
<a class="btn btn-default $class" href="$url">
	<span class="glyphicon glyphicon-remove"></span> Close
</a>
HTML;
	}

	/**
	 * edit
	 *
	 * @param string $title
	 * @param string $route
	 * @param array  $queries
	 * @param string $class
	 *
	 * @return  string
	 */
	public static function edit($title, $id, $route, $queries = [], $class = '')
	{
		$queries['id'] = $id;

		$url = Router::buildHtml($route, $queries);

		return <<<HTML
<a class="$class" href="$url">$title</a>
HTML;
	}

	/**
	 * apply
	 *
	 * @param string $class
	 *
	 * @return  string
	 */
	public static function apply($route, $queries = [], $class = '')
	{
		$queries['apply'] = 1;

		return static::save($route, $queries, $class);
	}

	/**
	 * save
	 *
	 * @param string $route
	 * @param array  $queries
	 * @param string $class
	 *
	 * @return  string
	 */
	public static function save($route, $queries = [], $class = '')
	{
		$url = Router::buildHtml($route, $queries);

		return <<<HTML
<a class="btn btn-success $class" href="#" onclick="RikiForm.post('$url')">
	<span class="glyphicon glyphicon-ok"></span> Save
</a>
HTML;
	}

	public static function reorder($route, $queries = [], $class = '')
	{
		$url = Router::buildHtml($route, $queries);

		return <<<HTML
<a class="btn btn-default $class" href="#" onclick="RikiForm.post('$url')">
	<span class="glyphicon glyphicon-refresh"></span> Reorder
</a>
HTML;
	}
}
