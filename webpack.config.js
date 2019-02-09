var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .cleanupOutputBeforeBuild()
    //.enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
    /*.addPlugin(new CopyWebpackPlugin([{
        from: './assets/css/bootstrap.css',
        to: 'css'
    }]))*/
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/bootstrap.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/popper.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/jquery-3.3.1.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/mdb.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/modules/wow.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/modules/waves.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/addons/datatables.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/jquery-form/dist/jquery.form.min.js',
        to: 'js'
    }]))
    .addStyleEntry('css/app', [
        './assets/scss/app.scss'
    ])
    .addStyleEntry('css/bootstrap', [
        './assets/css/bootstrap.css',
        //'./node_modules/typeahead/style.css'
    ])
    .addStyleEntry('css/login', [
        './assets/scss/login.scss'
    ])

    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/jquery-form/dist/jquery.form.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/symfony-collection/jquery.collection.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/bootstrap-typeahead.js',
        to: 'js'
    }]))
    /*.addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/typeahead.jquery.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/jquery-typeahead/dist/jquery.typeahead.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/jquery-typeahead/dist/jquery.typeahead.min.css',
        to: 'css'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/bloodhound.jquery.min.js',
        to: 'js'
    }]))*/

    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/number.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/css/number.css',
        to: 'css'
    }]))

    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/chosen-js/chosen.jquery.js',
        to: 'js'
    }]))

    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/css/chosen.css',
        to: 'css'
    }]))

    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/js/bootstrap-4-navbar.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './assets/css/bootstrap-4-navbar.css',
        to: 'css'
    }]))

    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/moment/moment.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/moment/locale/fr.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        to: 'js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        to: 'css'
    }]))

    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/js/froala_editor.min.js',
        to: 'froala/js'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/js/languages/fr.js',
        to: 'froala/js/languages'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/js/plugins/file.min.js',
        to: 'froala/js/plugins'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/js/plugins/lists.min.js',
        to: 'froala/js/plugins'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/js/plugins/fullscreen.min.js',
        to: 'froala/js/plugins'
    }]))
    .addStyleEntry('froala/css/froala', [
        './node_modules/froala-editor/css/froala_editor.min.css',
        './node_modules/froala-editor/css/froala_style.min.css'
    ])
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/css/plugins/file.min.css',
        to: 'froala/css/plugins'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/froala-editor/css/plugins/fullscreen.min.css',
        to: 'froala/css/plugins'
    }]))
    .addPlugin(new CopyWebpackPlugin([{
        from: './node_modules/readmore-js/readmore.min.js',
        to: 'js'
    }]))

;

var config = Encore.getWebpackConfig();

//disable amd loader
config.module.rules.unshift({
    parser: {
        amd: false,
    }
});

module.exports = config;
