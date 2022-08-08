let mix = require('laravel-mix');
require('./nova.mix');

mix.setPublicPath('dist')
    .copy('resources/lang/**/!(_lang.json)', 'dist/lang')
    .js('resources/js/tool.js', 'js')
    .vue({ version: 3 })
    .postCss('resources/css/tool.css', '/css/', [
        require("tailwindcss"),
    ])
    .nova('tightenco/nova-stripe');
