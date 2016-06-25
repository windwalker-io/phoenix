<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Language;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Filesystem\File;
use Windwalker\Filesystem\Folder;
use Windwalker\Ioc;
use Windwalker\Language\LanguageNormalize;
use Windwalker\Registry\Registry;
use Windwalker\Registry\RegistryHelper;

/**
 * The TranslatorHelper class.
 *
 * @since  1.0
 */
class TranslatorHelper
{
	/**
	 * loadAll
	 *
	 * @param string $package
	 * @param string $format
	 *
	 * @return  void
	 */
	public static function loadAll($package, $format = 'ini')
	{
		if (is_string($package))
		{
			$package = PackageHelper::getPackage($package);
		}

		if (!$package)
		{
			return;
		}

		$path = $package->getDir() . '/Resources/language';

		static::loadAllFromPath($path, $format, $package);
	}

	/**
	 * loadAllFromPath
	 *
	 * @param   string $path
	 * @param   string $format
	 * @param   string $package
	 */
	public static function loadAllFromPath($path, $format, $package = null)
	{
		$config = Ioc::getConfig();

		$locale = $config['language.locale']  ? : 'en-GB';
		$default = $config['language.default'] ? : 'en-GB';
		$locale = LanguageNormalize::toLanguageTag($locale);
		$default = LanguageNormalize::toLanguageTag($default);

		$localePath = $path . '/' . $locale;

		$files = array();

		if (is_dir($localePath))
		{
			$files = array_merge($files, (array) Folder::files($localePath, false, Folder::PATH_BASENAME));
		}

		$defaultPath = $path . '/' . $default;

		if (is_dir($defaultPath))
		{
			$files = array_merge($files, (array) Folder::files($defaultPath, false, Folder::PATH_BASENAME));
		}

		foreach ($files as $file)
		{
			$ext = File::getExtension($file);

			if (strcasecmp($ext, $format) !== 0)
			{
				continue;
			}

			Translator::loadFile(File::stripExtension($file), strtolower($format), $package);
		}
	}

	/**
	 * getOrphans
	 *
	 * @param boolean $flatten
	 * @param int     $stripPrefix
	 *
	 * @return array
	 */
	public static function getOrphans($flatten = true, $stripPrefix = 1)
	{
		$orphans = Translator::getInstance()->getOrphans();

		foreach ($orphans as $key => $value)
		{
			$value = explode('.', $key);

			$value = array_map('ucfirst', $value);

			foreach (range(1, $stripPrefix) as $i)
			{
				array_shift($value);
			}

			$value = implode(' ', $value);

			$orphans[$key] = $value;
		}

		if (!$flatten)
		{
			$reg = new Registry;

			foreach ($orphans as $key => $value)
			{
				$reg->set($key, $value);
			}

			$orphans = $reg->toArray();
		}

		return $orphans;
	}

	/**
	 * getFormattedOrphans
	 *
	 * @param string $format
	 *
	 * @return  string
	 */
	public static function getFormattedOrphans($format = 'ini')
	{
		$formatter = RegistryHelper::getFormatClass($format);

		$returns = array();
		$options = array();

		switch (strtolower($format))
		{
			case 'ini':

				$orphans = static::getOrphans();

				foreach ($orphans as $key => $value)
				{
					$key2 = explode('.', $key);

					if (isset($key2[1]))
					{
						$returns[$key2[1]][$key] = $value;

						continue;
					}

					$returns[$key] = $value;
				}

				break;

			case 'yaml':
			case 'yml':
				$options['inline'] = 99;

			default:
				$orphans = static::getOrphans(false);
				$returns = $orphans;
		}

		return $formatter::structToString($returns, $options);
	}

	/**
	 * dumpOrphans
	 *
	 * @param string $format
	 *
	 * @return  void
	 *
	 * @throws \Windwalker\Filesystem\Exception\FilesystemException
	 */
	public static function dumpOrphans($format = 'ini')
	{
		$format = strtolower($format);
		$ext = ($format === 'yaml') ? 'yml' : $format;

		$file = WINDWALKER_TEMP . '/language/orphans.' . $ext;

		if (!is_file($file))
		{
			Folder::create(dirname($file));
			file_put_contents($file, '');
		}

		$orphans = new Registry;
		$orphans->loadFile($file, $format, array('processSections' => true));

		$orphans->loadString(static::getFormattedOrphans($format), $format, array('processSections' => true));

		file_put_contents($file, $orphans->toString($format, array('inline' => 99)));
	}

	/**
	 * getFormatter
	 *
	 * @param   string $format
	 *
	 * @return  string
	 *
	 * @throws \DomainException
	 */
	public static function getFormatter($format)
	{
		$class = sprintf('Windwalker\Registry\Format\%sFormat', ucfirst($format));

		if (class_exists($class))
		{
			return $class;
		}

		throw new \DomainException(sprintf('Class: %s not exists', $class));
	}
}
