/**
 * Part of fusion project.
 *
 * @copyright  Copyright (C) 2018 Asikart.
 * @license    MIT
 */

const fusion = require('windwalker-fusion');

// The task `scss`
fusion.task('scss', function () {
  // Watch start
  fusion.watch([
    'asset/scss/**/*.scss',
  ]);
  // Watch end

  // Compile Start
  fusion.sass('asset/scss/**/*.scss', 'src/Resources/asset/css/');
  // Compile end
});

// The task `js`
fusion.task('js', function () {
  // Watch start
  fusion.watch([
    'asset/src/bootstrap/**/*.js',
    'asset/src/field/**/*.js'
  ]);
  // Watch end

  // Compile Start
  fusion.babel('asset/src/bootstrap/**/*.js', 'src/Resources/asset/js/bootstrap/');
  fusion.babel('asset/src/field/**/*.js', 'src/Resources/asset/js/field/');
  // Compile end
});

// The task `phoenix`
fusion.task('phoenix', function () {
  // Watch start
  fusion.watch([
    './asset/src/**/*.js'
  ]);
  // Watch end

  // Merge some core files
  fusion.babel(
    [
      'asset/src/phoenix.js',
      'asset/src/plugin.js',
      'asset/src/helper.js',
      'asset/src/ui.js',
      'asset/src/router.js',
      'asset/src/ajax.js',
      'asset/src/crypto.js',
      'asset/src/translator.js',
      'asset/src/legacy.js',
    ],
    'src/Resources/asset/js/phoenix/phoenix.js'
  );
  fusion.babel('asset/src/plugin/*.js', 'src/Resources/asset/js/phoenix/');

  // Vue
  fusion.babel('asset/src/vue/*.js', 'src/Resources/asset/js/vue/');
  // Compile end
});

// The task `install`
fusion.task('install', function () {
  const nodePath = 'node_modules';
  const destPath = 'src/Resources/asset';

  // Underscore
  fusion.copy(`${nodePath}/underscore/underscore.*`, `${destPath}/js/core/`);
  fusion.copy(`${nodePath}/underscore.string/dist/*`, `${destPath}/js/core/`);

  // Require JS
  fusion.copy(`${nodePath}/requirejs/require.js`, `${destPath}/js/core/require.js`);

  // Backbone
  fusion.copy(`${nodePath}/backbone/backbone.js`, `${destPath}/js/core/backbone.js`);
  fusion.copy(`${nodePath}/backbone/backbone-min.js`, `${destPath}/js/core/backbone.min.js`);

  // Punycode
  fusion.copy(`${nodePath}/punycode/punycode.js`, `${destPath}/js/string/punycode.js`);

  // jQuery
  fusion.copy(`${nodePath}/jquery/dist/jquery.js`, `${destPath}/js/jquery/`);
  fusion.copy(`${nodePath}/jquery/dist/jquery.min.js`, `${destPath}/js/jquery/`);
  fusion.copy(`${nodePath}/jquery/dist/jquery.min.map`, `${destPath}/js/jquery/`);

  // Vue
  fusion.copy(`${nodePath}/vue/dist/vue.js`, `${destPath}/js/vue/vue.js`);
  fusion.copy(`${nodePath}/vue/dist/vue.min.js`, `${destPath}/js/vue/vue.min.js`);
  fusion.copy(`${nodePath}/vue-resource/dist/vue-resource.js`, `${destPath}/js/vue/vue-resource.js`);
  fusion.copy(`${nodePath}/vue-resource/dist/vue-resource.min.js`, `${destPath}/js/vue/vue-resource.min.js`);
  fusion.copy(`${nodePath}/vue-strap/dist/vue-strap.js`, `${destPath}/js/vue/vue-strap.js`);
  fusion.copy(`${nodePath}/vue-strap/dist/vue-strap.min.js`, `${destPath}/js/vue/vue-strap.min.js`);
  fusion.copy(`${nodePath}/vue-strap/dist/vue-strap.js.map`, `${destPath}/js/vue/vue-strap.js.map`);
  fusion.copy(`${nodePath}/vue-router/dist/vue-router.js`, `${destPath}/js/vue/vue-router.js`);
  fusion.copy(`${nodePath}/vue-router/dist/vue-router.min.js`, `${destPath}/js/vue/vue-router.min.js`);
  fusion.copy(`${nodePath}/vuex/dist/vuex.js`, `${destPath}/js/vue/vuex.js`);
  fusion.copy(`${nodePath}/vuex/dist/vuex.min.js`, `${destPath}/js/vue/vuex.min.js`);
  fusion.copy(`${nodePath}/vue2-animate/dist/*`, `${destPath}/css/vue/`);

  // Moment.js
  fusion.copy(`${nodePath}/moment/moment.js`, `${destPath}/js/datetime/moment.js`);
  fusion.copy(`${nodePath}/moment/locale/en-gb.js`, `${destPath}/js/datetime/locale/en-gb.js`);
  fusion.copy(`${nodePath}/moment/locale/zh-tw.js`, `${destPath}/js/datetime/locale/zh-tw.js`);
  fusion.copy(`${nodePath}/moment/locale/zh-cn.js`, `${destPath}/js/datetime/locale/zh-cn.js`);
  fusion.copy(`${nodePath}/moment/locale/ja.js`, `${destPath}/js/datetime/locale/ja-jp.js`);
  fusion.copy(`${nodePath}/moment/locale/ko.js`, `${destPath}/js/datetime/locale/ko-kr.js`);

  fusion.copy(`${nodePath}/moment-timezone/moment-timezone.js`, `${destPath}/js/datetime/moment-timezone.js`);
  fusion.copy(`${nodePath}/moment-timezone/builds/moment-timezone.min.js`, `${destPath}/js/datetime/moment-timezone.min.js`);

  // Silicone
  fusion.copy(`${nodePath}/silicone/dist/*`, `${destPath}/css/silicone/`);

  // Sprintf.js
  fusion.copy(`${nodePath}/sprintf-js/dist/sprintf*`, `${destPath}/js/core/`);

  // Ivia
  fusion.copy(`${nodePath}/ivia/dist/ivia.js`, `${destPath}/js/ivia/ivia.js`);
  fusion.copy(`${nodePath}/ivia/dist/ivia.min.js`, `${destPath}/js/ivia/ivia.min.js`);
  fusion.copy(`${nodePath}/ivia/dist/ivia.js.map`, `${destPath}/js/ivia/ivia.js.map`);

  // Font Awesome
  fusion.copy(`${nodePath}/font-awesome/css/font-awesome.css`, `${destPath}/css/font-awesome.css`);
  fusion.copy(`${nodePath}/font-awesome/css/font-awesome.css.map`, `${destPath}/css/font-awesome.css.map`);
  fusion.copy(`${nodePath}/font-awesome/css/font-awesome.min.css`, `${destPath}/css/font-awesome.min.css`);
  fusion.copy(`${nodePath}/font-awesome/fonts/*`, `${destPath}/fonts/`);

  // Bootstrap
  fusion.copy(`${nodePath}/bootstrap/dist/css/bootstrap.*`, `${destPath}/css/bootstrap/4/`);
  fusion.copy(`${nodePath}/bootstrap/dist/js/bootstrap.bundle.*`, `${destPath}/js/bootstrap/4/`);

  fusion.copy(`${nodePath}/bootstrap/scss/**/*`, `${destPath}/css/bootstrap/4/scss/`);

  fusion.copy(`${nodePath}/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.*`, `${destPath}/css/bootstrap/`);
  fusion.copy(`${nodePath}/eonasdan-bootstrap-datetimepicker/build/js/*`, `${destPath}/js/bootstrap/`);

  // Select2
  fusion.copy(`${nodePath}/select2/dist/js/select2.js`, `${destPath}/js/select2/`);
  fusion.copy(`${nodePath}/select2/dist/js/select2.min.js`, `${destPath}/js/select2/`);
  fusion.copy(`${nodePath}/select2/dist/css/*`, `${destPath}/css/select2/`);
  fusion.copy(`${nodePath}/select2-bootstrap4-theme/dist/*`, `${destPath}/css/select2/bs4/`);

  // jQuery minicolors
  fusion.copy(`${nodePath}/@claviska/jquery-minicolors/jquery.minicolors.css`, `${destPath}/css/jquery/`);
  fusion.copy(`${nodePath}/@claviska/jquery-minicolors/jquery.minicolors.js`, `${destPath}/js/jquery/`);
  fusion.copy(`${nodePath}/@claviska/jquery-minicolors/jquery.minicolors.png`, `${destPath}/css/jquery/`);
  fusion.copy(`${nodePath}/@claviska/jquery-minicolors/jquery.minicolors.min.js`, `${destPath}/js/jquery/`);

  // Sortable.js
  fusion.copy(`${nodePath}/sortablejs/*.js`, `${destPath}/js/sortablejs/`);

  // Velocity
  fusion.copy(`${nodePath}/velocity-animate/velocity.*`, `${destPath}/js/velocity/`);

  // SystemJS
  fusion.copy(`${nodePath}/systemjs/dist/*`, `${destPath}/js/core/systemjs/`);

  // URL Polyfill
  fusion.copy(`${nodePath}/url-polyfill/*.js`, `${destPath}/js/core/`);
});

fusion.default(['js', 'scss', 'phoenix']);

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
