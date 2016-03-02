module.exports = function(grunt) {
  grunt.initConfig({
    phpunit: {
      classes: {
        dir: 'tests/*.php'
      },
      options: {
        bin: 'vendor/bin/phpunit',
        colors: false
      }
    },

    watch: {
      tests: {
        files: ['**/*.php'],
        tasks: ['phpunit']
      }
    }
  });

grunt.loadNpmTasks('grunt-phpunit');
grunt.loadNpmTasks('grunt-contrib-watch');

grunt.registerTask('test', ['phpunit']);
grunt.registerTask('default', ['watch']);
}

