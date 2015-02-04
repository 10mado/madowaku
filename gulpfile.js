"use strict";

var gulp = require('gulp')
  , compass = require('gulp-compass')
  , uglify = require('gulp-uglify')
  , jshint = require('gulp-jshint')
  , shell = require('gulp-shell')
;

var paths = {
  sass: ['assets/sass/**/*.scss','assets/sass/**/*.sass'],
  js:   ['assets/js/*.js'],
  bowerjs: [
    'bower_components/jquery/dist/jquery.js',
  ],
  bowercss: [
  ],
  bowerfont: [
  ]
}

gulp.task('compass', function() {
  gulp.src(paths.sass)
    .pipe(compass({
      config_file: 'assets/config/compass.rb',
      css: 'public/css',
      sass: 'assets/sass',
      bundle_exec: true
    }))
    .on('error', function(err) {
      return console.log(err.stack);
    })
    .pipe(shell([
      'php src/create_asset_files.php'
    ]))
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

gulp.task('bower', function() {
  gulp.src(paths.bowerjs)
    .pipe(uglify({preserveComments: 'some'}))
    .pipe(gulp.dest('public/js/vendor'))
    .pipe(shell([
      'php src/create_asset_files.php'
    ]));
  gulp.src(paths.bowercss)
    .pipe(gulp.dest('public/css/vendor'))
    .pipe(shell([
      'php src/create_asset_files.php'
    ]));
  gulp.src(paths.bowerfont)
    .pipe(gulp.dest('public/css/vendor/font'));
});

gulp.task('server', shell.task([
  'php -c public/.user.ini -S lvh.me:8080 -t public'
]));

gulp.task('watch', function() {
  gulp.watch(paths.sass, ['compass']);
  gulp.watch(paths.js, ['js']);
});

gulp.task('default', ['compass', 'js', 'watch']);
gulp.task('dev', ['default', 'server']);
