var Encore = require('@symfony/webpack-encore');
const { VueLoaderPlugin } = require('vue-loader');

Encore
    .setOutputPath('public/build')
    .setPublicPath('/build')
    .addEntry('app', '/assets/app.js')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableVueLoader()  
    .enableSingleRuntimeChunk() 
    .addPlugin(new VueLoaderPlugin());

module.exports = Encore.getWebpackConfig();
