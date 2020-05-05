const gulp = require('gulp')
const sass = require('gulp-sass')
const cssmin = require('gulp-clean-css')
const plumber = require('gulp-plumber')
const babel = require('gulp-babel')
const jsmin = require('gulp-uglify')
const rename = require('gulp-rename');

let error_stop = true

function src(list) {
  if(error_stop) {
    return gulp.src(list)
  }else{
    return gulp.src(list).pipe(plumber())
  }
}

gulp.task('sass', ()=>{
  return src(
    [
      'assets/_scss/style.scss',
      'assets/_scss/editor.scss',
    ]
  )
  .pipe(sass())
  .pipe(cssmin())
  .pipe(gulp.dest('assets/css/'))
})

gulp.task('scripts', ()=>{
  return src(
    [
      'assets/_js/*.js'
    ]
  )
  .pipe(babel({
    presets: ['@babel/env']
  }))
  .pipe(jsmin())
  .pipe(rename({
    suffix:'.min'
  }))
  .pipe(gulp.dest('./assets/js/'));
})

gulp.task('watch', ()=>{
  error_stop = false
  gulp.watch(
    [
      'assets/_scss/*.scss'
    ],
    gulp.series(
        'sass'
    )
  )
  gulp.watch(
    [
      'assets/_js/*.js'
    ],
    gulp.series(
        'scripts'
    )
  )
})

gulp.task('default', gulp.series('watch'))

// copy dist ////////////////////////////////////////////////

gulp.task('dist', function() {
	return gulp.src(
			[
				'./*.php',
				'./**/*.php',
				'./**/*.txt',
				'./**/*.css',
				'./**/*.scss',
				'./**/*.bat',
				'./**/*.rb',
				'./**/*.eot',
				'./**/*.svg',
				'./**/*.ttf',
				'./**/*.woff',
				'./images/**',
				'./inc/**',
				'./js/**',
				'./languages/**',
				'./library/**',
				"!./tests/**",
				"!./dist/**",
				"!./node_modules/**/*.*"
			], {
				base: './'
			}
		)
		.pipe(gulp.dest('dist/mae-ticket')); // distディレクトリに出力
});
