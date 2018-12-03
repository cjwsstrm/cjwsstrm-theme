// Gulp.js configuration
'use strict';

const

  // source and build folders
  // dir = {
  //   src         : 'src/',
  //   build       : '/var/www/wp-content/themes/mytheme/'
  // },

  // Gulp and plugins
  gulp          = require('gulp'),
  changed       = require('gulp-changed'),
  sftp          = require('gulp-sftp'),
  ftp           = require('vinyl-ftp'),
  env           = require('gulp-env'),
  notify        = require('gulp-notify'),
  del           = require('del'),
  gutil         = require('gulp-util'),
  // newer         = require('gulp-newer'),
  imagemin      = require('gulp-imagemin'),
  sass          = require('gulp-sass'),
  postcss       = require('gulp-postcss'),
  deporder      = require('gulp-deporder'),
  concat        = require('gulp-concat'),
  stripdebug    = require('gulp-strip-debug'),
  uglify        = require('gulp-uglify')
;

env('.env.json');

// Browser-sync
var browsersync = false;


// PHP settings
// const php = {
//   src           : dir.src + 'template/**/*.php',
//   build         : dir.build
// };

gulp.task('default', (done) => {
  console.log('Use gulp commands for updating this plugin:');
  console.log('gulp clean: clean the dist folder');
  console.log('gulp init: initialize dist folder');
  console.log('gulp deploy: deploy changed files to remote SFTP');
  console.log('gulp watch: watch src and auto deploy all changes');
  done();
});

gulp.task('clean', () => {
    return del('dist/**/*.*');
});

gulp.task('init', () => {
  return gulp.src('src/**/*.*')
      .pipe(gulp.dest('dist'));
});


gulp.task('watch', gulp.series('init', () => {
  console.log('Watching src files for changes...');
  return gulp.watch('src/**/*', gulp.series('deploy'));
}));

gulp.task('deploy', () => {
  let stream = gulp.src('src/**/*.*')
      .pipe(changed('dist'))
      .pipe(gulp.dest('dist'));

  let type = process.env.TYPE.toUpperCase();
  let host = process.env.HOST;
  let port = process.env.PORT || (type == 'SFTP' ? 22 : 21);
  let user = process.env.USER;
  let pass = process.env.PASS;
  let remotePath = process.env.REMOTE_PATH;

  if (type == 'SFTP') {
      stream = stream.pipe(sftp({
          host: host,
          port: port,
          user: user,
          pass: pass,
          remotePath: remotePath
      }));
  } else {
      let remote = ftp.create({
          host: host,
          port: port,
          user: user,
          pass: pass,
          // remotePath: remotePath,
          parallel: 1,
      });
      stream = stream.pipe(remote.dest(remotePath));
  }

  return stream.pipe(notify("Successfully uploaded file: <%= file.relative %>."));
});