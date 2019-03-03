const gulp = require('gulp');
const htmlmin = require('gulp-htmlmin');
const count = require('gulp-count');
const print = require('gulp-print').default;

gulp.task('copy_bootstrap_sass', function() {
    gulp.src('node_modules/bootstrap/scss/**/*')
    .pipe(gulp.dest('assets/sass/bootstrap/'));
});

gulp.task('copy_js', function() {
    gulp.src([
        'node_modules/popper.js/dist/umd/popper.js',
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js'
    ])
    .pipe(gulp.dest('assets/js/'))
    .pipe(print())
    .pipe(count('## files have been copied'));
});

gulp.task('minify', () => {
    return gulp.src(['public/**/*.html', '!public/assets/**/*'])
        .pipe(htmlmin({
            collapseWhitespace: true
        }))
        .pipe(gulp.dest('public'))
        .pipe(print())
        .pipe(count('## files processed.'));
});

gulp.task('default', ['copy_css', 'copy_js'], function () {
    console.log('Done!');
});