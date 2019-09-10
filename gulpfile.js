const {watch, src, dest, series, parallel}  = require('gulp');
const browserSync                           = require('browser-sync').create();
const connect                               = require('gulp-connect-php');
const sass                                  = require('gulp-sass');
const rename                                = require('gulp-rename');
const postcss                               = require('gulp-postcss');
const autoprefixer                          = require('autoprefixer');
const cssnano                               = require('cssnano');

// Config object with paths
const config = {
    public : {
        scss    : 'app/public/style/**/*.scss',
        js      : 'app/public/js/*.js',
        php     : 'app/public/*.php'
    },

    dist : {
        base : 'app/dist'
    }
}

// Task for convert SCSS file to CSS
// Task for adding prefix
// Task for minimize css file
function cssTask(done){
    src(config.public.scss)
        .pipe(sass({outputStyle:'expanded'}))
        .pipe(rename({suffix:'.min'}))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(dest(config.dist.base))
    done();
};

// Task for watch files
function watchFiles(done){
    watch(config.public.scss, series(cssTask, reload));
    watch(config.public.js, series(reload));
    watch(config.public.php, series(reload));
}

// Task for live reload
function liveReload(done){
    connect.server({}, function(){
        browserSync.init({
            proxy:'http://localhost/trans/domi-manage/app/public/index.php'
        });
    });
    done();
}

// Task for reload
function reload(done){
    browserSync.reload();
    done();
}



exports.css = cssTask;
exports.dev = parallel(cssTask, watchFiles, liveReload);
