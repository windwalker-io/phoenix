/**
 * Part of fusion project.
 *
 * @copyright  Copyright (C) 2018 Asikart.
 * @license    MIT
 */

const fusion = require('windwalker-fusion');

// The task `js`
fusion.task('js', function () {
  // Watch start
  fusion.watch([
    'src/Resources/asset/js/phoenix/**/*.js',
    'src/Resources/asset/js/bootstrap/tab-state.js',
    'src/Resources/asset/js/bootstrap/button-radio.js',
  ]);
  // Watch end

  // Compile Start
  fusion.js('src/Resources/asset/js/phoenix/**/*.js');
  fusion.js('src/Resources/asset/js/bootstrap/tab-state.js');
  fusion.js('src/Resources/asset/js/bootstrap/button-radio.js');
  // Compile end
});

// The task `scss`
fusion.task('scss', function () {
  // Watch start
  fusion.watch([
    'src/Resources/asset/css/**/*.scss',
  ]);
  // Watch end

  // Compile Start
  fusion.sass('src/Resources/asset/css/bootstrap/switch.scss');
  // Compile end
});

fusion.default(['js', 'scss']);

/*
 * APIs
 *
 * Compile entry:
 * fusion.js(source, dest, options = {})
 * fusion.babel(source, dest, options = {})
 * fusion.ts(source, dest, options = {})
 * fusion.typeScript(source, dest, options = {})
 * fusion.css(source, dest, options = {})
 * fusion.less(source, dest, options = {})
 * fusion.sass(source, dest, options = {})
 * fusion.copy(source, dest, options = {})
 *
 * Live Reload:
 * fusion.livereload(source, dest, options = {})
 * fusion.reload(file)
 *
 * Gulp proxy:
 * fusion.src(source, options)
 * fusion.dest(path, options)
 * fusion.task(name, deps, fn)
 * fusion.watch(glob, opt, fn)
 *
 * Stream Helper:
 * fusion.through(handler) // Same as through2.obj()
 *
 * Config:
 * fusion.disableNotification()
 * fusion.enableNotification()
 */
