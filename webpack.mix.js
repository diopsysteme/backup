const mix = require('laravel-mix');

mix
    .js('resource/js/app.js','public/js')
    .js('resource/js/swagger.js','public/js')
    .version();