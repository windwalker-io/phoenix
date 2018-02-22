/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

var gulp = require("gulp"),//http://gulpjs.com/
  util = require("gulp-util"),//https://github.com/gulpjs/gulp-util
  less = require("gulp-less"),//https://www.npmjs.org/package/gulp-less
  sass = require("gulp-sass"),//https://www.npmjs.org/package/gulp-less
  autoprefixer = require('gulp-autoprefixer'),//https://www.npmjs.org/package/gulp-autoprefixer
  minifycss = require('gulp-minify-css'),//https://www.npmjs.org/package/gulp-minify-css
  uglify = require('gulp-uglify'),//https://www.npmjs.org/package/gulp-minify-css
  rename = require('gulp-rename'),//https://www.npmjs.org/package/gulp-rename
  sourcemaps = require('gulp-sourcemaps'),
  log = util.log,
  filter = require('gulp-filter');;

const sassFiles = [
  'src/Resources/asset/css/**/*.scss',
];
const entryFiles = [
  'src/Resources/asset/css/bootstrap/switch.scss',
];

gulp.task("sass", () => {
  log("Generate CSS files " + (new Date()).toString());
  entryFiles.map((e) => {
    gulp.src(e)
      .pipe(sourcemaps.init())
      .pipe(sass({ style: 'expanded' }))
      .pipe(autoprefixer("last 3 version", "safari 5", "ie 8", "ie 9"))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(file => file.base))
      .pipe(filter('**/*.css'))
      .pipe(rename({suffix: '.min'}))
      .pipe(minifycss())
      .pipe(gulp.dest(file => file.base));
  });
});

const jsFiles = [
  './src/Resources/asset/js/phoenix/**/*.js',
  'src/Resources/asset/js/bootstrap/tab-state.js',
  'src/Resources/asset/js/bootstrap/button-radio.js',
];
const jsEntryFiles = [
  './src/Resources/asset/js/phoenix/**/*.js',
  'src/Resources/asset/js/bootstrap/tab-state.js',
  'src/Resources/asset/js/bootstrap/button-radio.js',
];

var condition = '!src/Resources/asset/js/**/*.min.js';

gulp.task("js", () => {
  log("Generate JS files " + (new Date()).toString());
  jsEntryFiles.map((e) => {
    gulp.src([e, '!./**/*.min.js'])
      //.pipe(jshint())
      //.pipe(filter(['**', condition]))
      .pipe(sourcemaps.init())
      .pipe(rename({suffix: '.min'}))
      .pipe(uglify().on('error', e => console.error(e)))
      .pipe(gulp.dest(file => file.base))
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest(file => file.base));
  });
});

gulp.task("watch", () => {
  log("Watching JS/SCSS files for modifications");
  gulp.watch(sassFiles, ["sass"]);
  gulp.watch([jsFiles, '!./**/*.min.js'], ["js"]);
});

gulp.task("default", ["sass", "js", "watch"]);
