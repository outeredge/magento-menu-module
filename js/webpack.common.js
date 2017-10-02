const webpack = require('webpack');
const path = require('path');

module.exports = {
    entry: [
        'babel-polyfill',
        './src/index.js'
    ],
    output: {
        filename: 'menu-editor.js'
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                use: ['babel-loader'],
                exclude: /node_modules/
            }
        ]
    }
};