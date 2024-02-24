const mix = require('laravel-mix');
const sourceAssets = 'resources/assets/';
// application assert
mix.js(sourceAssets + 'js/app.js', 'public/js')
    .sass(sourceAssets + 'sass/app.scss', 'public/css');

//theme assert
mix.scripts([
    sourceAssets + 'js/theme/sidebarmenu.js',
    sourceAssets + 'js/theme/custom.js'
], 'public/js/theme/script.js');

mix.sass(sourceAssets + 'sass/theme/dark/style.scss', 'public/css/theme/dark');
mix.sass(sourceAssets + 'sass/theme/light/style.scss', 'public/css/theme/light');
mix.sass(sourceAssets + 'sass/theme/horizontal/style.scss', 'public/css/theme/horizontal');
mix.sass(sourceAssets + 'sass/theme/colors/blue.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/blue-dark.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/default.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/default-dark.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/green.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/green-dark.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/megna.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/megna-dark.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/purple.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/purple-dark.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/red.scss', 'public/css/theme/colors');
mix.sass(sourceAssets + 'sass/theme/colors/red-dark.scss', 'public/css/theme/colors');

// vendors assert
const nodePath = 'node_modules/';
const bowerPath = 'public/components/';
//vendors for login
mix.scripts([
    nodePath + 'jquery/dist/jquery.js',
    nodePath + 'angular/angular.min.js',
    nodePath + 'angular-utils-pagination/dirPagination.js',
    nodePath + 'popper.js/dist/umd/popper.js',
    nodePath + 'jquery-slimscroll/jquery.slimscroll.js',
    nodePath + 'malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
    nodePath + 'node-waves/dist/waves.js',
    nodePath + 'sticky-kit/dist/sticky-kit.js',
    nodePath + 'jquery-sparkline/jquery.sparkline.js',
    nodePath + 'jquery.switcher/dist/switcher.js',
    nodePath + 'bootstrap/dist/js/bootstrap.js',
    nodePath + 'sweetalert2/dist/sweetalert2.js',
    bowerPath + 'semantic/dist/semantic.js',
    nodePath + 'toastr/build/toastr.min.js',
    nodePath + 'chart.js/dist/Chart.min.js'
], 'public/js/vendor/basic.js');

mix.styles([
    nodePath + 'node-waves/dist/waves.css',
    nodePath + 'bootstrap/dist/css/bootstrap.css',
    nodePath + 'malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css',
    nodePath + 'sweetalert2/dist/sweetalert2.css',
    bowerPath + 'semantic/dist/semantic.css',
    bowerPath + 'toastr/build/toastr.css'
], 'public/css/vendor/basic.css');


//Compile table vendors
mix.scripts([
    nodePath + 'datatables/media/js/jquery.dataTables.js'
], 'public/js/vendor/table.js').styles([
    nodePath + 'datatables/media/css/jquery.dataTables.css'
], 'public/css/vendor/table.css');


//Compile faces vendors
mix.scripts([
    nodePath + 'tracking/build/tracking-min.js',
    nodePath + 'tracking/build/data/face-min.js'
], 'public/js/vendor/face.js').sass(
    sourceAssets + 'sass/face.sass', 'public/css/vendor/face.css');

//Compile table vendors
mix.scripts([
    nodePath + 'lightgallery/dist/js/lightgallery-all.js'
], 'public/js/vendor/gallery.js').styles([
    nodePath + 'lightgallery/dist/css/lightgallery.css'
], 'public/css/vendor/gallery.css');


//Compile dropzone
mix.scripts([
    nodePath + 'dropzone/dist/dropzone.js',
    nodePath + 'dropzone/dist/dropzone-amd-module.js',
    sourceAssets + 'js/vendor/form.js'
], 'public/js/vendor/dropzone.js').styles([
    nodePath + 'dropzone/dist/basic.css',
    nodePath + 'dropzone/dist/dropzone.css'
], 'public/css/vendor/dropzone.css');

//Compile form vendors
mix.scripts([
    // nodePath + 'jquery-datepicker/jquery-datepicker.js',
    nodePath + 'moment/min/moment.min.js',
    nodePath + 'bootstrap-select-v4/dist/js/bootstrap-select.js',
    nodePath + 'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
    nodePath + 'clockpicker/dist/jquery-clockpicker.js',
    nodePath + 'dropzone/dist/dropzone.js',
    nodePath + 'dropzone/dist/dropzone-amd-module.js',
    sourceAssets + 'js/vendor/form.js'
], 'public/js/vendor/form.js').styles([
    nodePath + 'bootstrap-select-v4/dist/css/bootstrap-select.css',
    nodePath + 'bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
    nodePath + 'clockpicker/dist/jquery-clockpicker.css',
    nodePath + 'dropzone/dist/basic.css',
    nodePath + 'dropzone/dist/dropzone.css'
], 'public/css/vendor/form.css');

mix.scripts([
    nodePath + 'underscore/underscore.js'
], 'public/js/vendor/object-helper.js');

mix.scripts([
    nodePath + 'jquery-steps/build/jquery.steps.js'
], 'public/js/vendor/steps.js').styles([
    sourceAssets + 'sass/theme/_inc/css/steps.css'
], 'public/css/vendor/steps.css');


mix.scripts([
    sourceAssets + 'js/vendor/collapse-table.js'
], 'public/js/vendor/collapse-table.js').styles([
    sourceAssets + 'sass/theme/_inc/css/collapse-table.scss'
], 'public/css/vendor/collapse-table.css');

mix.scripts([
    bowerPath + 'slidereveal/dist/jquery.slidereveal.js'
], 'public/js/vendor/slidereveal.js');

mix.scripts([
    'resources/assets/js/_inc/barcode-detect.js',
    nodePath + 'jsbarcode/dist/JsBarcode.all.js'
], 'public/js/vendor/barcode.js');

// copy images
mix.copyDirectory(sourceAssets + 'images', 'public/images');
mix.copyDirectory(sourceAssets + 'plugins', 'public/plugins');
mix.copyDirectory(sourceAssets + 'template', 'public/template');
mix.copyDirectory(nodePath + 'lightgallery/dist/fonts', 'public/css/fonts');
mix.copyDirectory(bowerPath + 'semantic/dist/themes/default/assets/fonts', 'public/css/vendor/themes/default/assets/fonts');
mix.copyDirectory(nodePath + 'lightgallery/dist/img', 'public/css/img');