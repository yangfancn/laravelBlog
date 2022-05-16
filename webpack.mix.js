const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  target: ['web', 'es5']
})
  .browserSync({
    proxy: 'http://blog.test',
    files: [
      'app/**/*.php',
      'resources/views/**/*.php',
      'public/admin/js/*.js',
      'public/admin/css/*.css',
    ]
  })
  .js('resources/admin/js/app.js', 'public/admin/js')
  .js('resources/admin/js/dashboard.js', 'public/admin/js')
  .js('resources/admin/js/list.js', 'public/admin/js')
  .sass('resources/admin/scss/app.scss', 'public/admin/css')
  .copy('resources/admin/pictures', 'public/admin/pictures')
  .copy('resources/admin/data', 'public/admin/data')
  .options({
    // processCssUrls: false
  })
  .extract();

mix.version();
