/*jslint browser: false, node: true, maxlen: 120 */

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package   \TDM\Escher
 * @license   https://raw.github.com/twistdigital/escher/master/LICENSE
 *
 * Copyright (c) 2000-2014, Twist Digital Media
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this
 *    list of conditions and the following disclaimer in the documentation and/or
 *    other materials provided with the distribution.
 *
 * 3. Neither the name of the {organization} nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

"use strict";

var gulp, gutil, minifycss, autoprefixer, uglify, concat, path, svgmin, imagemin, styles, scripts, bitmaps, vectors;

/**
 * Configuration stuff
 */

styles = {
    "input": "lib/stylesheets/**/*.css",
    "output": "public/stylesheets.css"
};

scripts = {
    "input": "lib/javascript/**/*.js",
    "output": "public/javascript.js"
};

bitmaps = {
    "input": ["public/**/*.png", "public/**/*.jpg", "public/**/*.jpeg", "public/**/*.gif"],
    "output": "public"
};

vectors = {
    "input": "public/**/*.svg",
    "output": "public"
};

/**
 * End configuration
 */

// Gulp core stuff
gulp  = require('gulp');
gutil = require('gulp-util');

// Concat lets us cat/rename files
concat = require("gulp-concat");

// CSS Dependencies
autoprefixer = require('gulp-autoprefixer');
minifycss = require('gulp-minify-css');

// Javascript Dependencies
uglify = require("gulp-uglify");

// Image dependencies
imagemin = require("gulp-imagemin");
svgmin   = require("gulp-svgmin");

// Use the path library to split down the config output into path and filename.
path = require("path");

// Compile stylesheets with optional minification
gulp.task("styles", function () {
    return gulp.src(styles.input)
        .pipe(autoprefixer("last 2 versions", "ie 8", "ie 9"))
        .pipe(concat(path.basename(styles.output)))
        .pipe(gutil.env.production ? minifycss() : gutil.noop())
        .pipe(gulp.dest(path.dirname(styles.output)));
});

// Compile javascript with optional minification
gulp.task("scripts", function () {
    return gulp.src(scripts.input)
        .pipe(concat(path.basename(scripts.output)))
        .pipe(gutil.env.production ? uglify() : gutil.noop())
        .pipe(gulp.dest(path.dirname(scripts.output)));
});

// Optimize vector images
gulp.task("optimize-vectors", function () {
    return gulp.src(vectors.input)
        .pipe(svgmin())
        .pipe(gulp.dest(vectors.output));
});

// Optimize bitmapped images
gulp.task("optimize-bitmaps", function () {
    return gulp.src(bitmaps.input)
        .pipe(imagemin())
        .pipe(gulp.dest(bitmaps.output));
});

// Rebuild makes the styles and scripts
gulp.task("rebuild", ["styles", "scripts"]);

// Optimize resizes the images
gulp.task("optimize", ["optimize-bitmaps", "optimize-vectors"]);

// Default runs everything
gulp.task("default", ["rebuild", "optimize"]);

// Watch job rebuilds and then watches for changes
gulp.task("watch", ["rebuild"], function () {
    gulp.watch([scripts.input], ["scripts"]);
    gulp.watch([styles.input], ["styles"]);
});
