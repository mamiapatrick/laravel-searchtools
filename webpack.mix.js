const {mix} = require('laravel-mix');
mix.sass('src/resources/assets/sass/search_tools.scss', 'src/resources/assets/css/search_tools.css')
    .js('src/resources/assets/js/search_tools.js', 'src/resources/assets/js/search_tools.min.js');