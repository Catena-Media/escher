/*jslint node, maxlen: 120 */

"use strict";

    // Core gulp stuff
var gulp = require("gulp-help")(require("gulp")),
    gutil = require("gulp-util"),
    bump = require("gulp-bump"),
    concat = require("gulp-concat"),
    noop = require("lodash").noop,
    stream = require("vinyl-source-stream"),
    streamify = require("gulp-streamify"),

    // Stylesheet stuff
    sass = require("gulp-sass"),
    minifycss = require("gulp-minify-css"),
    autoprefixer = require("gulp-autoprefixer"),

    // Javascript stuff
    uglify = require("gulp-uglify"),
    browserify = require("browserify"),
    babelify = require("babelify"),
    strip = require("gulp-strip-debug"),

    // Image stuff
    imagemin = require("gulp-imagemin");

// Compile stylesheets with optional minification
gulp.task("styles", "Rebuild the SASS.", function () {

    var sassConfig = {style: "expanded"},
        prefixerConfig = {},
        squish = gutil.noop(),
        prefix;

    if (gutil.env.production) {
        squish = minifycss();
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
        squish = gutil.noop(),
        cleanup = gutil.noop();

    if (!options.debug) {
        squish = streamify(uglify());
        cleanup = streamify(strip());
    }

    return browserify("./lib/javascript/main.js", options)
        .transform(babelify)
        .bundle()
        .pipe(stream("javascript.js"))
        .pipe(cleanup)
        .pipe(squish)
        .pipe(gulp.dest("./public/"));
}, {
    options: {
        production: "  production ready"
    }
});

// Optimize images
gulp.task("optimize", "Optimize images.", function () {

    var images = [
        './public/images/**/*.png',
        './public/images/**/*.jpg',
        './public/images/**/*.jpeg',
        './public/images/**/*.gif',
        './public/images/**/*.svg'
    ];

    return gulp.src(images)
        .pipe(imagemin())
        .pipe(gulp.dest("./public/images/"));
});

// Increment version numbers
gulp.task("bump", "Increment version number.", function () {

    var version;

    if (gutil.env.version) {
        version = {version: gutil.env.version};
    }

    return gulp.src(["./package.json", "./bower.json"])
        .pipe(bump(version))
        .pipe(gulp.dest("./"));
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
