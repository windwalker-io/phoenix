<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    LGPL-2.0-or-later
 */

namespace Phoenix\Utilities;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Filter\OutputFilter;

/**
 * The SlugifyHelper class.
 *
 * @since  1.8.13
 */
class SlugHelper
{
    /**
     * Make slug safe.
     *
     * @param string      $alias
     * @param bool        $utf8
     * @param string|null $default
     * @param int         $defaultLimit
     *
     * @return  string
     * @throws \Exception
     */
    public static function safe(string $alias, bool $utf8 = false, ?string $default = null, $defaultLimit = 8): string
    {
        $slug = static::slugify($alias, $utf8, $default, $defaultLimit);

        if (trim($slug) === '') {
            $slug = static::getDefaultSlug();
        }

        return $slug;
    }

    /**
     * slugify
     *
     * @param string      $alias
     * @param bool        $utf8
     * @param string|null $default
     * @param int         $defaultLimit
     *
     * @return  string
     */
    public static function slugify(
        string $alias,
        bool $utf8 = false,
        ?string $default = null,
        $defaultLimit = 8
    ): string {
        if ($alias === '' && (string) $default !== '') {
            $words = static::breakWords($default);

            $words = array_slice($words, 0, $defaultLimit);

            $alias = implode(' ', $words);
        }

        if ($utf8) {
            return OutputFilter::stringURLUnicodeSlug($alias);
        }

        if (function_exists('transliterator_transliterate')) {
            $alias = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $alias);
        }

        return OutputFilter::stringURLSafe($alias);
    }

    /**
     * breakWords
     *
     * @param string $text
     *
     * @return  array
     *
     * @since  1.8.13
     */
    public static function breakWords(string $text): array
    {
        // @see https://stackoverflow.com/a/43882448
        preg_match_all(
            '/\p{Hangul}|\p{Hiragana}|\p{Han}|\p{Katakana}|(\p{Latin}+)|(\p{Cyrillic}+)/u',
            str($text)->collapseWhitespaces()->__toString(),
            $matches
        );

        return $matches[0] ?? [];
    }

    /**
     * getDefaultSlug
     *
     * @return  string
     *
     * @throws \Exception
     *
     * @since  1.8.13
     */
    public static function getDefaultSlug(): string
    {
        return OutputFilter::stringURLSafe(Chronos::current('Y-m-d-H-i-s'));
    }
}
