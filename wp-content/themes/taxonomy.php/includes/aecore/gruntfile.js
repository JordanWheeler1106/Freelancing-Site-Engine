module.exports = function(grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        phpunit: {
            marketenginetest: {
                configuration : './tests/phpunit.xml'
            },
            options: {
                bin: './vendor/bin/phpunit',
                bootstrap: './tests/bootstrap.php',
                colors: true
            }
        },
        watch: {
            phpunit: {
                files: ['tests/*/*.php','tests/*.php','includes/*.php', 'includes/*/*.php'],
                tasks: ['phpunit']
            }
        }
    });
    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    // Default task(s).
    grunt.registerTask('default', ['phpunit']);
};