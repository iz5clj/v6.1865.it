const gulp = require('gulp');
const htmlmin = require('gulp-htmlmin');
const count = require('gulp-count');
const print = require('gulp-print').default;

gulp.task('copy_css', function () {
    gulp.src('node_modules/bootstrap/dist/css/bootstrap.css')
        .pipe(gulp.dest('themes/unify261/assets/css/'));
});

gulp.task('copy_boot_sass', function () {
    gulp.src('node_modules/bootstrap/scss/**/*')
        .pipe(gulp.dest('themes/unify261/assets/scss/bootstrap/'))
        .pipe(count('## files (bootstrap) copied.'));
});

gulp.task('copy_unify_sass', function () {
    gulp.src('unify261/html/assets/include/scss/**/*')
        .pipe(gulp.dest('themes/unify261/assets/scss/unify261/'))
        .pipe(count('## files (unify) copied.'));
});

gulp.task('copy_unify_css', function () {
    gulp.src(['unify261/html/assets/**/*.css'], {base: 'unify261/html'})
    .pipe(gulp.dest('themes/unify261/static/'))
    .pipe(count('#### css files (unify) copied.'));
});

gulp.task('copy_unify_js', function () {
    gulp.src(['unify261/html/assets/**/*.js'], {base: 'unify261/html'})
    .pipe(gulp.dest('themes/unify261/static/'))
    .pipe(count('#### js files (unify) copied.'));
});

gulp.task('copy_js', function () {
    gulp.src([
            'node_modules/popper.js/dist/umd/popper.js',
            'node_modules/jquery/dist/jquery.js',
            'node_modules/bootstrap/dist/js/bootstrap.js',
            'node_modules/jquery-migrate/dist/jquery-migrate.js'
        ])
        .pipe(gulp.dest('themes/unify261/assets/js/'));
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