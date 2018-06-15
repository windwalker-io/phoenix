<?php
/**
 * Class Minify_CSS_UriRewriter
 *
 * @package Minify
 */

namespace Phoenix\Minify;

/**
 * Rewrite file-relative URIs as root-relative in CSS files
 *
 * @package Minify
 * @author  Stephen Clay <steve@mrclay.org>
 *
 * @note    This is modified version of https://github.com/mrclay/minify/blob/3.x/lib/Minify/CSS/UriRewriter.php
 */
class CssUriRewriter
{
    /**
     * rewrite() and rewriteRelative() append debugging information here
     *
     * @var string
     */
    public static $debugText = '';

    /**
     * In CSS content, rewrite file relative URIs as root relative
     *
     * @param string $css
     *
     * @param string $currentDir The directory of the current CSS file.
     *
     * @param string $docRoot    The document root of the web site in which
     *                           the CSS file resides (default = $_SERVER['DOCUMENT_ROOT']).
     *
     * @param array  $symlinks   (default = array()) If the CSS file is stored in
     *                           a symlink-ed directory, provide an array of link paths to
     *                           target paths, where the link paths are within the document root. Because
     *                           paths need to be normalized for this to work, use "//" to substitute
     *                           the doc root in the link paths (the array keys). E.g.:
     *                           <code>
     *                           array('//symlink' => '/real/target/path') // unix
     *                           array('//static' => 'D:\\staticStorage')  // Windows
     *                           </code>
     *
     * @return string
     */
    public static function rewrite($css, $currentDir, $docRoot = null, $symlinks = [])
    {
        static::$_docRoot    = static::_realpath($docRoot ? $docRoot : $_SERVER['DOCUMENT_ROOT']);
        static::$_currentDir = static::_realpath($currentDir);
        static::$_symlinks   = [];

        // normalize symlinks
        foreach ($symlinks as $link => $target) {
            $link = ($link === '//')
                ? static::$_docRoot
                : str_replace('//', static::$_docRoot . '/', $link);

            $link = strtr($link, '/', DIRECTORY_SEPARATOR);

            static::$_symlinks[$link] = static::_realpath($target);
        }

        static::$debugText .= "docRoot    : " . static::$_docRoot . "\n" . "currentDir : " . static::$_currentDir . "\n";

        if (static::$_symlinks) {
            static::$debugText .= "symlinks : " . var_export(static::$_symlinks, 1) . "\n";
        }

        static::$debugText .= "\n";

        $css = static::_trimUrls($css);
        $css = static::_owlifySvgPaths($css);

        // rewrite
        $css = preg_replace_callback(
            '/@import\\s+([\'"])(.*?)[\'"]/'
            , [get_called_class(), '_processUriCB'], $css
        );

        $css = preg_replace_callback(
            '/url\\(\\s*([\'"](.*?)[\'"]|[^\\)\\s]+)\\s*\\)/'
            , [get_called_class(), '_processUriCB'], $css
        );

        $css = static::_unOwlify($css);

        return $css;
    }

    /**
     * In CSS content, prepend a path to relative URIs
     *
     * @param string $css
     *
     * @param string $path The path to prepend.
     *
     * @return string
     */
    public static function prepend($css, $path)
    {
        static::$_prependPath = $path;

        $css = static::_trimUrls($css);
        $css = static::_owlifySvgPaths($css);

        // append
        $css = preg_replace_callback(
            '/@import\\s+([\'"])(.*?)[\'"]/',
            [get_called_class(), '_processUriCB'],
            $css
        );

        $css = preg_replace_callback(
            '/url\\(\\s*([\'"](.*?)[\'"]|[^\\)\\s]+)\\s*\\)/',
            [get_called_class(), '_processUriCB'],
            $css
        );

        $css = static::_unOwlify($css);

        static::$_prependPath = null;

        return $css;
    }

    /**
     * Get a root relative URI from a file relative URI
     *
     * <code>
     * Minify_CSS_UriRewriter::rewriteRelative(
     *       '../img/hello.gif'
     *     , '/home/user/www/css'  // path of CSS file
     *     , '/home/user/www'      // doc root
     * );
     * // returns '/img/hello.gif'
     *
     * // example where static files are stored in a symlinked directory
     * Minify_CSS_UriRewriter::rewriteRelative(
     *       'hello.gif'
     *     , '/var/staticFiles/theme'
     *     , '/home/user/www'
     *     , array('/home/user/www/static' => '/var/staticFiles')
     * );
     * // returns '/static/theme/hello.gif'
     * </code>
     *
     * @param string $uri            file relative URI
     *
     * @param string $realCurrentDir realpath of the current file's directory.
     *
     * @param string $realDocRoot    realpath of the site document root.
     *
     * @param array  $symlinks       (default = array()) If the file is stored in
     *                               a symlink-ed directory, provide an array of link paths to
     *                               real target paths, where the link paths "appear" to be within the document
     *                               root. E.g.:
     *                               <code>
     *                               array('/home/foo/www/not/real/path' => '/real/target/path') // unix
     *                               array('C:\\htdocs\\not\\real' => 'D:\\real\\target\\path')  // Windows
     *                               </code>
     *
     * @return string
     */
    public static function rewriteRelative($uri, $realCurrentDir, $realDocRoot, $symlinks = [])
    {
        // prepend path with current dir separator (OS-independent)
        $path = strtr($realCurrentDir, '/', DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . strtr($uri, '/', DIRECTORY_SEPARATOR);

        static::$debugText .= "file-relative URI  : {$uri}\n"
            . "path prepended     : {$path}\n";

        // "unresolve" a symlink back to doc root
        foreach ($symlinks as $link => $target) {
            if (0 === strpos($path, $target)) {
                // replace $target with $link
                $path = $link . substr($path, strlen($target));

                static::$debugText .= "symlink unresolved : {$path}\n";

                break;
            }
        }

        // strip doc root
        $path = substr($path, strlen($realDocRoot));

        static::$debugText .= "docroot stripped   : {$path}\n";

        // fix to root-relative URI
        $uri = strtr($path, '/\\', '//');
        $uri = static::removeDots($uri);

        static::$debugText .= "traversals removed : {$uri}\n\n";

        return $uri;
    }

    /**
     * Remove instances of "./" and "../" where possible from a root-relative URI
     *
     * @param string $uri
     *
     * @return string
     */
    public static function removeDots($uri)
    {
        $uri = str_replace('/./', '/', $uri);

        // inspired by patch from Oleg Cherniy
        do {
            $uri = preg_replace('@/[^/]+/\\.\\./@', '/', $uri, 1, $changed);
        } while ($changed);

        return $uri;
    }

    /**
     * Get realpath with any trailing slash removed. If realpath() fails,
     * just remove the trailing slash.
     *
     * @param string $path
     *
     * @return mixed path with no trailing slash
     */
    protected static function _realpath($path)
    {
        $realPath = realpath($path);

        if ($realPath !== false) {
            $path = $realPath;
        }

        return rtrim($path, '/\\');
    }

    /**
     * Directory of this stylesheet
     *
     * @var string
     */
    private static $_currentDir = '';

    /**
     * DOC_ROOT
     *
     * @var string
     */
    private static $_docRoot = '';

    /**
     * directory replacements to map symlink targets back to their
     * source (within the document root) E.g. '/var/www/symlink' => '/var/realpath'
     *
     * @var array
     */
    private static $_symlinks = [];

    /**
     * Path to prepend
     *
     * @var string
     */
    private static $_prependPath = null;

    /**
     * @param string $css
     *
     * @return string
     */
    private static function _trimUrls($css)
    {
        return preg_replace(
            '/
            url\\(      # url(
            \\s*
            ([^\\)]+?)  # 1 = URI (assuming does not contain ")")
            \\s*
            \\)         # )
        /x', 'url($1)', $css
        );
    }

    /**
     * @param array $m
     *
     * @return string
     */
    private static function _processUriCB($m)
    {
        // $m matched either '/@import\\s+([\'"])(.*?)[\'"]/' or '/url\\(\\s*([^\\)\\s]+)\\s*\\)/'
        $isImport = ($m[0][0] === '@');
        // determine URI and the quote character (if any)
        if ($isImport) {
            $quoteChar = $m[1];
            $uri       = $m[2];
        } else {
            // $m[1] is either quoted or not
            $quoteChar = ($m[1][0] === "'" || $m[1][0] === '"')
                ? $m[1][0]
                : '';

            $uri = ($quoteChar === '')
                ? $m[1]
                : substr($m[1], 1, strlen($m[1]) - 2);
        }

        if ($uri === '') {
            return $m[0];
        }

        // if not root/scheme relative and not starts with scheme
        if (!preg_match('~^(/|[a-z]+\:)~', $uri)) {
            // URI is file-relative: rewrite depending on options
            if (static::$_prependPath === null) {
                $uri = static::rewriteRelative($uri, static::$_currentDir, static::$_docRoot, static::$_symlinks);
            } else {
                $uri = static::$_prependPath . $uri;
                if ($uri[0] === '/') {
                    $root         = '';
                    $rootRelative = $uri;
                    $uri          = $root . static::removeDots($rootRelative);
                } elseif (preg_match('@^((https?\:)?//([^/]+))/@', $uri, $m) && (false !== strpos($m[3], '.'))) {
                    $root         = $m[1];
                    $rootRelative = substr($uri, strlen($root));
                    $uri          = $root . static::removeDots($rootRelative);
                }
            }
        }

        return $isImport
            ? "@import {$quoteChar}{$uri}{$quoteChar}"
            : "url({$quoteChar}{$uri}{$quoteChar})";
    }

    /**
     * Mungs some inline SVG URL declarations so they won't be touched
     *
     * @link https://github.com/mrclay/minify/issues/517
     * @see  _unOwlify
     *
     * @param string $css
     *
     * @return string
     */
    private static function _owlifySvgPaths($css)
    {
        return preg_replace('~\b((?:clip-path|mask|-webkit-mask)\s*\:\s*)url(\(\s*#\w+\s*\))~', '$1owl$2', $css);
    }

    /**
     * Undo work of _owlify
     *
     * @see _owlifySvgPaths
     *
     * @param string $css
     *
     * @return string
     */
    private static function _unOwlify($css)
    {
        return preg_replace('~\b((?:clip-path|mask|-webkit-mask)\s*\:\s*)owl~', '$1url', $css);
    }
}
