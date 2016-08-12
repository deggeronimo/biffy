// Karma configuration
// http://karma-runner.github.io/0.10/config/configuration-file.html

module.exports = function(config) {
  config.set({
    // base path, that will be used to resolve files and exclude
    basePath: '',

    // testing framework to use (jasmine/mocha/qunit/...)
    frameworks: ['jasmine'],

    // list of files / patterns to load in the browser
    files: [
      'theme/bower_components/jquery/dist/jquery.js',
      'theme/bower_components/underscore/underscore.js',
      'theme/bower_components/enquire/dist/enquire.js',
      'theme/bower_components/select2/select2.js',
      'theme/bower_components/toastr/toastr.js',
      'theme/bower_components/jScrollPane/script/jquery.mousewheel.js',
      'theme/bower_components/jScrollPane/script/mwheelIntent.js',
      'theme/bower_components/jScrollPane/script/jquery.jscrollpane.min.js',
      'theme/bower_components/angular/angular.js',
      'theme/bower_components/angular-cookies/angular-cookies.js',
      'theme/bower_components/angular-sanitize/angular-sanitize.js',
      'theme/bower_components/angular-animate/angular-animate.js',
      'theme/bower_components/angular-bootstrap/ui-bootstrap-tpls.js',
      'theme/bower_components/angular-ui-router/release/angular-ui-router.js',
      'theme/bower_components/ng-table/ng-table.js',
      'theme/bower_components/lodash/dist/lodash.compat.js',
      'theme/bower_components/restangular/dist/restangular.js',
      'theme/bower_components/ui-router-menus/dist/angular-ui-router-menus.js',
      'theme/src/app.js',
      'theme/src/**/*.js',
      'theme/components/**/*.js',
      'theme/src/**/*.html',
      'theme/components/**/*.html'
    ],

    preprocessors: {
      '**/*.html': 'html2js'
    },

    ngHtml2JsPreprocessor: {
      stripPrefix: 'theme/'
    },

    // list of files / patterns to exclude
    exclude: [],

    // web server port
    port: 8080,

    // level of logging
    // possible values: LOG_DISABLE || LOG_ERROR || LOG_WARN || LOG_INFO || LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: false,

    // Start these browsers, currently available:
    // - Chrome
    // - ChromeCanary
    // - Firefox
    // - Opera
    // - Safari (only Mac)
    // - PhantomJS
    // - IE (only Windows)
    browsers: ['PhantomJS'],


    // Continuous Integration mode
    // if true, it capture browsers, run tests and exit
    singleRun: false
  });
};
