module.exports = function (grunt) {

    "use strict";

    var javascriptSource, stylesheetSource;

    stylesheetSource = 'lib/stylesheets/style.scss';
    javascriptSource = [
        'lib/javascript/hjax.js'
    ];

    grunt.initConfig({

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'public/stylesheet.css': stylesheetSource
                }
            },
            dev: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'public/stylesheet.css': stylesheetSource
                }
            }
        },

        uglify: {
            minify: {
                src: javascriptSource,
                dest: 'public/javascript.js'
            }
        },

        concat: {
            dist: {
                src: javascriptSource,
                dest: 'public/javascript.js'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.registerTask('default', ['sass:dev', 'concat']);
    grunt.registerTask('deploy', ['sass:dist', 'uglify']);
};
