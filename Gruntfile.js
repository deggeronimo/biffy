'use strict';

module.exports = function (grunt) {

  // Load grunt tasks automatically, when needed
  require('jit-grunt')(grunt, {
    useminPrepare: 'grunt-usemin',
    ngtemplates: 'grunt-angular-templates',
    cdnify: 'grunt-google-cdn',
    protractor: 'grunt-protractor-runner',
    injector: 'grunt-asset-injector',
    buildcontrol: 'grunt-build-control'
  });

  // Time how long tasks take. Can help when optimizing build times
  require('time-grunt')(grunt);

  // Define the configuration for all the tasks
  grunt.initConfig({

    // Project settings
    biffy: {
      client: 'theme', //project folder is public folder in development mode
      dist: 'public' //dist folder is the final public folder in production mode
    },
    open: {
      server: {
        url: grunt.file.isFile('.url') ? grunt.file.read('.url') : 'http://biffy.localhost' //@todo this needs to be loaded from .env?
      }
    },
    watch: {
      injectJS: { //any changes to app/components js files need to be injected into _index.html
        files: [
          '<%= biffy.client %>/{src,components}/**/*.js',
          '!<%= biffy.client %>/src/purchasing/bak/**/*.js',
          '!<%= biffy.client %>/{src,components}/**/*.spec.js',
          '!<%= biffy.client %>/{src,components}/**/*.mock.js',
          '!<%= biffy.client %>/src/app.js'],
        tasks: ['injector:scripts']
      },
      injectCss: {
        files: [
          '<%= biffy.client %>/{src,components}/**/*.css'
        ],
        tasks: ['injector:css']
      },
      jsTest: {
        files: [
          '<%= biffy.client %>/{src,components}/**/*.spec.js',
          '<%= biffy.client %>/{src,components}/**/*.mock.js'
        ],
        tasks: ['newer:jshint:all', 'karma']
      },
      injectLess: {
        files: [
          '<%= biffy.client %>/{src,components}/**/*.less'],
        tasks: ['injector:less']
      },
      less: {
        files: [
          '<%= biffy.client %>/{src,components}/**/*.less'],
        tasks: ['less', 'autoprefixer']
      },
      gruntfile: {
        files: ['Gruntfile.js']
      },
      livereload: {
        files: [
          '<%= biffy.client %>/{app,src,components}/**/*.css',
          '<%= biffy.client %>/{src,components}/**/*.html',
          '<%= biffy.client %>/{src,components}/**/*.js',
         '!<%= biffy.client %>/{src,components}/**/*.spec.js',
         '!<%= biffy.client %>/{src,components}/**/*.mock.js',
          '<%= biffy.client %>/assets/img/{,*//*}*.{png,jpg,jpeg,gif,webp,svg}'
        ],
        options: {
          livereload: true
        }
      }
    },

    // Make sure code styles are up to par and there are no obvious mistakes
    jshint: {
      options: {
        jshintrc: '<%= biffy.client %>/.jshintrc',
        reporter: require('jshint-stylish')
      },
      all: [
        '<%= biffy.client %>/{src,components}/**/*.js',
       '!<%= biffy.client %>/components/theme/**/*.js', //do not worry about forza files, they have a pretty bad run for jshint.
       '!<%= biffy.client %>/{src,components}/**/*.spec.js',
       '!<%= biffy.client %>/{src,components}/**/*.mock.js'
      ],
      test: {
        src: [
          '<%= biffy.client %>/{src,components}/**/*.spec.js',
          '<%= biffy.client %>/{src,components}/**/*.mock.js'
        ]
      }
    },

    // Empties folders to start fresh
    clean: {
      temp: {
        files: [{
          dot: true,
          src: [
            '<%= biffy.client %>/temp' //All files in temp folder are generated, let us clean them.
          ]
        }]
      },
      client: {
        files: [{
          dot: true,
          src: [
            '<%= biffy.client %>/app' //All files in app folder are generated, let us clean them.
          ]
        }]
      },
      dist: {
        files: [{
          dot: true,
          src: [
            '<%= biffy.dist %>/*',
            '!<%= biffy.dist %>/.git*',
            '!<%= biffy.dist %>/.openshift',
            '!<%= biffy.dist %>/Procfile'
          ]
        }]
      }
    },

    // Add vendor prefixed styles
    autoprefixer: {
      options: {
        browsers: ['last 2 version']
      },
      client: { //perform autoprefixer on theme/app files
        files: [{
          expand: true,
          cwd: '<%= biffy.client %>/app',
          src: '{,*/}*.css',
          dest: '<%= biffy.client %>/app'
        }]
      }
    },

    // Automatically inject Bower components into the app
    wiredep: {
      target: {
        src: '<%= biffy.client %>/_index.html',
        ignorePath: '<%= biffy.client %>/',
        exclude: [
          /html5shiv/, //polyfills are excluded here but included manually for < IE9 browsers
          /respond/,
          /excanvas/,
          /bootstrap.css/, //bootstrap less files  get compiled with theme less files
          /font-awesome/, //font-awesome less files get compiled with theme less files
          /angular-sanitize/ // replaced by textAngular-sanitize
        ]
      }
    },

    // Renames files for browser caching purposes
    // Use this section to revved name of any file in public folder
    rev: {
      dist: {
        files: {
          src: [
            '<%= biffy.dist %>/app/**/*.js',
            '<%= biffy.dist %>/app/**/*.css'
          ]
        }
      }
    },

    // Reads HTML for usemin blocks to enable smart builds that automatically
    // concat, minify and revision files. Creates configurations in memory so
    // additional tasks can operate on them
    useminPrepare: {
      html: ['<%= biffy.client %>/_index.html'],
      options: {
        staging: '<%= biffy.client %>/temp',
        dest: '<%= biffy.client %>'
      }
    },

    // Performs rewrites based on rev and the useminPrepare configuration
    usemin: {
      html: ['<%= biffy.dist %>/{,*/}*.html'],
      css: ['<%= biffy.dist %>/{,*/}*.css'],
      js: ['<%= biffy.dist %>/{,*/}*.js'],
      options: {
        assetsDirs: [
          '<%= biffy.dist %>',
          '<%= biffy.dist %>/assets/img'
        ],
        // This is so we update image references in our ng-templates
        patterns: {
          js: [
            [/(assets\/img\/.*?\.(?:gif|jpeg|jpg|png|webp|svg))/gm, 'Update the JS to reference our revved images']
          ]
        }
      }
    },

    // The following *-min tasks produce minified files in the dist folder
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= biffy.client %>/assets/img',
          src: '{,*/}*.{png,jpg,jpeg,gif}',
          dest: '<%= biffy.dist %>/assets/img'
        }]
      }
    },

    svgmin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= biffy.client %>/assets/img',
          src: '{,*/}*.svg',
          dest: '<%= biffy.dist %>/assets/img'
        }]
      }
    },

    // Allow the use of non-minsafe AngularJS files. Automatically makes it
    // minsafe compatible so Uglify does not destroy the ng references
    ngAnnotate: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= biffy.client %>/temp',
          src: '**/*.js',
          dest: '<%= biffy.client %>/temp'
        }]
      }
    },

    // Package all the html partials into a single javascript payload
    ngtemplates: {
      options: {
        // This should be the name of your apps angular module
        module: 'biffyApp',
        htmlmin: {
          collapseBooleanAttributes: true,
          collapseWhitespace: true,
          removeAttributeQuotes: true,
          removeEmptyAttributes: true,
          removeRedundantAttributes: true,
          removeScriptTypeAttributes: true,
          removeStyleLinkTypeAttributes: true
        },
        usemin: 'app/app.js'
      },
      main: {
        cwd: '<%= biffy.client %>',
        src: ['{src,components}/**/*.html'],
        dest: '<%= biffy.client %>/temp/templates.js'
      }
    },

    // Replace Google CDN references
    cdnify: {
      dist: {
        html: ['<%= biffy.dist %>/*.html']
      }
    },

    // Copies remaining files to places other tasks can use
    copy: {
      dist: {
        files: [{
          expand: true,
          dot: true,
          cwd: '<%= biffy.client %>',
          dest: '<%= biffy.dist %>',
          src: [
            '*.{ico,png,txt}',
            '.htaccess',
            'bower_components/**/*',
            'app/**/*',
            'assets/img/{,*/}*.{webp}',
            'assets/fonts/**/*',
            'assets/json/**/*',
            '_index.html',
            'index.php'
          ]
        }, {
          expand: true,
          cwd: '<%= biffy.client %>/img', //@todo needs work
          dest: '<%= biffy.dist %>/assets/img',
          src: ['generated/*']
        }]
      },
      styles: {
        expand: true,
        cwd: '<%= biffy.client %>',
        dest: '<%= biffy.client %>/app', //@todo ? this may need work
        src: ['{src,components}/**/*.css']
      }
    },

    buildcontrol: {
      options: {
        dir: 'dist',
        commit: true,
        push: true,
        connectCommits: false,
        message: 'Built %sourceName% from commit %sourceCommit% on branch %sourceBranch%'
      },
      github: {
        options: {
          remote: 'origin',
          branch: 'customers'
        }
      }
    },

    // Run some tasks in parallel to speed up the build process
    concurrent: {
      client: [
        'less',
      ],
      test: [
        'less',
      ],
      dist: [
        'less',
        'imagemin',
        'svgmin'
      ]
    },

    // Test settings
    karma: {
      unit: {
        configFile: 'karma.conf.js',
        singleRun: true
      }
    },

    protractor: {
      options: {
        configFile: 'protractor.conf.js'
      },
      chrome: {
        options: {
          args: {
            browser: 'chrome'
          }
        }
      }
    },

    phpunit: {
      classes: {
        dir: 'tests/'
      },
      options: {
        bin: 'vendor/bin/phpunit',
        bootstrap: 'bootstrap/autoload.php',
        colors: true
      }
    },

    // Compiles Less to CSS
    less: {
      options: {
        paths: [
          '<%= biffy.client %>/bower_components',
          '<%= biffy.client %>/src',
          '<%= biffy.client %>/components'
        ]
      },
      client: {
        files: {
          '<%= biffy.client %>/app/app.css' : '<%= biffy.client %>/src/app.less' //create app/app.css based on src/app.less
        }
      }
    },

    injector: {
      options: {

      },
      // Inject application script files into _index.html (doesn't include bower)
      scripts: {
        options: {
          transform: function(filePath) {
            filePath = filePath.replace('/theme/', '');
            return '<script src="' + filePath + '"></script>';
          },
          starttag: '<!-- injector:js -->',
          endtag: '<!-- endinjector -->'
        },
        files: {
          '<%= biffy.client %>/_index.html': [
            ['<%= biffy.client %>/{src,components}/**/*.js',
            '!<%= biffy.client %>/src/app.js',
            '!<%= biffy.client %>/src/purchasing/bak/**/*.js',
            '!<%= biffy.client %>/{src,components}/**/*.spec.js',
            '!<%= biffy.client %>/{src,components}/**/*.mock.js']
          ]
        }
      },

      // Inject component less into app.less
      less: {
        options: {
          transform: function(filePath) {
            filePath = filePath.replace('/theme/src/', '');
            filePath = filePath.replace('/theme/components/', '');
            return '@import \'' + filePath + '\';';
          },
          starttag: '// injector',
          endtag: '// endinjector'
        },
        files: {
          '<%= biffy.client %>/src/app.less': [
          '<%= biffy.client %>/{src,components}/**/*.less',
          '!<%= biffy.client %>/src/app.less',
          '!<%= biffy.client %>/components/theme/less/mixins.less',
          '!<%= biffy.client %>/components/theme/less/layout.less',
          '!<%= biffy.client %>/components/theme/less/variables.less'
          ]
        }
      },

      // Inject component css into _index.html
      css: {
        options: {
          transform: function(filePath) {
            filePath = filePath.replace('/theme/', '');
            return '<link rel="stylesheet" href="' + filePath + '">';
          },
          starttag: '<!-- injector:css -->',
          endtag: '<!-- endinjector -->'
        },
        files: {
          '<%= biffy.client %>/_index.html': [
            '<%= biffy.client %>/app/**/*.css' //inject css files from app folder instead of src/components
          ]
        }
      }
    }
  });

  // Used for delaying livereload until after server has restarted
  grunt.registerTask('wait', function () {
    grunt.log.ok('Waiting for server reload...');

    var done = this.async();

    setTimeout(function () {
      grunt.log.writeln('Done waiting!');
      done();
    }, 1500);
  });

  grunt.registerTask('express-keepalive', 'Keep grunt running', function() {
    this.async();
  });

  grunt.registerTask('serve', function (target) {

    grunt.task.run([
      'clean:client',
      'injector:less',
      'concurrent:client',
      'injector',
      'wiredep',
      'autoprefixer',
      'wait',
      'open',
      'watch'
    ]);

  });

  grunt.registerTask('server', function () {
    grunt.log.warn('The `server` task has been deprecated. Use `grunt serve` to start a server.');
    grunt.task.run(['serve']);
  });

  grunt.registerTask('test', function(target) {

    if (target === 'server') {
      return grunt.task.run([
        'phpunit'
      ]);
    }

    else if (target === 'client') {
      return grunt.task.run([
        'clean:client',
        'injector:less',
        'concurrent:test',
        'injector',
        'autoprefixer',
        'karma'
      ]);
    }

    else if (target === 'e2e') {
      return grunt.task.run([
        'clean:client',
        'injector:less',
        'concurrent:test',
        'injector',
        'wiredep',
        'autoprefixer',
        'protractor'
      ]);
    }

    else grunt.task.run([
        'test:server',
        'test:client'
      ]);
  });

  grunt.registerTask('build', [
    'clean',
    'injector:less',
    'concurrent:dist',
    'injector',
    'wiredep',
    'useminPrepare',
    'autoprefixer',
    'ngtemplates',
    'concat',
    'ngAnnotate',
    'cdnify',
    'cssmin',
    'uglify',
    'copy:dist',
    'rev',
    'usemin'
  ]);

  grunt.registerTask('default', [
    'newer:jshint',
    //'test', //Tests needs improvement before enabling this line.
    'build'
  ]);

};
