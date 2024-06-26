const path = require('path');

module.exports = {
    mode: 'production',

    entry: {
        'admin': './src/Admin/index.js',
        'gallery': './src/Gallery/index.js',
        'installation': './src/Installation/index.js',
    },
    output: {
        filename: '[name].main.js',
        path: path.resolve(`${__dirname}/../public`, 'js'),
    },
};