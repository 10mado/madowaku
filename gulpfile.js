'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    bourbon = require('node-bourbon').includePaths,
    autoprefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    jshint = require('gulp-jshint'),
    shell = require('gulp-shell');

var paths = {
  sass: ['assets/sass/**/*.scss','assets/sass/**/*.sass'],
  js:   ['assets/js/*.js'],
  vendorjs: [
    'node_modules/jquery/dist/jquery.min.js'
  ],
  vendorcss: [
  ],
  vendorfont: [
  ]
}

gulp.task('sass', function() {
  gulp.src(paths.sass)
    .pipe(sass({
      outputStyle: 'compressed',
      includePaths: ['sass'].concat(bourbon)
    }))
    .on('error', function(err) {
      return console.log(err.stack);
    })
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false,
      remove: false
    }))
    .pipe(gulp.dest('public/css'));
});


gulp.task('js', function() {
  gulp.src(paths.js)
    .on('error', function(err) {
      return console.log(err.stack);
    })
    .pipe(jshint('assets/config/.jshintrc'))
    .pipe(jshint.reporter('default'))
    .pipe(uglify())
    .pipe(gulp.dest('public/js'))
    .pipe(shell([
      'php src/create_asset_files.php'
    ]));
});

gulp.task('vendor', function() {
  gulp.src(paths.vendorjs, {base: 'node_modules'})
    .pipe(gulp.dest('public/js/vendor'))
    .pipe(shell([
      'php src/create_asset_files.php'
    ]));
  gulp.src(paths.vendorcss, {base: 'node_modules'})
    .pipe(gulp.dest('public/css/vendor'))
    .pipe(shell([
      'php src/create_asset_files.php'
    ]));
  gulp.src(paths.vendorfont, {base: 'node_modules'})
    .pipe(gulp.dest('public/css/vendor/font'));
});

gulp.task('server', shell.task([
  'php -c public/.user.ini -S lvh.me:8080 -t public'
]));

gulp.task('watch', function() {
  gulp.watch(paths.sass, ['sass']);
  gulp.watch(paths.js, ['js']);
});

gulp.task('default', ['sass', 'js', 'watch']);
gulp.task('dev', ['default', 'server']);
