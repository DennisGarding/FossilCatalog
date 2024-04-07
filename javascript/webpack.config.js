const path = require('path');

module.exports = {
    entry: {
        'admin': './src/Admin/index.js',
        'gallery': './src/Gallery/index.js'
    },
    output: {
        filename: '[name].main.js',
        path: path.resolve(`${__dirname}/../public`, 'js'),
    },

    // Uncomment the following line if you want to edit the JS files (Enable watch mode)
    // watch: true,
};