const gulp = require('gulp');
const sass = require('gulp-sass');
const cssmin = require('gulp-cssmin');
const plumber = require('gulp-plumber');

let error_stop = true

function src(list) {
  if(error_stop) {
    return gulp.src(list)
  }else{
    return gulp.src(list).pipe(plumber())
  }
}

gulp.task('sass', ()=>{
  error_stop = false
  return src(
    [
      'assets/_scss/style.scss'
    ]
  )
  .pipe(sass())
  .pipe(cssmin())
  .pipe(gulp.dest('assets/css/'))
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
})