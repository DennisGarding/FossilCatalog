const path = require('path');

module.exports = {
    mode: 'development',

    entry: {
        'admin': './src/Admin/index.js',
        'gallery': './src/Gallery/index.js',
        'installation': './src/Installation/index.js'
    },
    output: {
        filename: '[name].main.js',
        path: path.resolve(`${__dirname}/../public`, 'js'),
    },

    watch: true,
    watchOptions: {
        aggregateTimeout: 200,
        poll: 1000,
    },

    optimization: {
        minimize: false,
    },
};