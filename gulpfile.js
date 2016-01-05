/*jslint node, maxlen: 120 */

"use strict";

    // Core gulp stuff
var gulp = require("gulp-help")(require("gulp")),
    gutil = require("gulp-util"),
    concat = require("gulp-concat"),
    noop = require("lodash").noop,
    stream = require("vinyl-source-stream"),
    streamify = require("gulp-streamify"),

    // Stylesheet stuff
    sass = require("gulp-sass"),
    cssnano = require("gulp-cssnano"),
    autoprefixer = require("gulp-autoprefixer"),

    // Javascript stuff
    uglify = require("gulp-uglify"),
    browserify = require("browserify"),
    babelify = require("babelify");

// Compile stylesheets with optional minification
gulp.task("styles", "Rebuild the SASS.", function () {

    var sassConfig = {style: "expanded"},
        prefixerConfig = {},
        squish = gutil.noop(),
        prefix;

    if (gutil.env.production) {
        squish = cssnano();
    } else {
        sassConfig.errLogToConsole = true;
        sassConfig.sourceComments = "map";
        prefixerConfig.map = {inline: true};
    }

    prefix = autoprefixer(
        "last 3 versions",
        "Explorer >= 10",
        "Android >= 4.0",
        "Blackberry 10",
        "> 5%",
        prefixerConfig
    );

    return gulp.src("./lib/stylesheets/main.scss")
        .pipe(sass(sassConfig))
        .pipe(prefix)
        .pipe(concat("stylesheet.css"))
        .pipe(squish)
        .pipe(gulp.dest("./public/"));
}, {
    options: {
        production: "  production ready"
    }
});

// Compile javascript with optional minification
gulp.task("scripts", "Rebuild the javascript.", function () {

    var options = {debug: !gutil.env.production},
        squish = gutil.noop();

    if (!options.debug) {
        squish = uglify({
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        });
    }

    return browserify("./lib/javascript/main.js", options)
        .transform(babelify)
        .bundle()
        .pipe(stream("javascript.js"))
        .pipe(streamify(squish))
        .pipe(gulp.dest("./public/"));
}, {
    options: {
        production: "  production ready"
    }
});

// Rebuild everything
gulp.task("rebuild", "Rebuild everything for the new site.", ["scripts", "styles"], noop, {
    options: {
        production: "  production ready"
    }
});

// Watch job rebuilds and then watches for changes
gulp.task("watch", "start watch, rebuilding on change", ["rebuild"], function () {
    gulp.watch(["./lib/javascript/**/*.js"], ["scripts"]);
    gulp.watch(["./lib/stylesheets/**/*.scss"], ["styles"]);
});

// Show help by default
gulp.task("default", false, ["help"]);
