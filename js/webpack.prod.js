const path = require('path');
const merge = require('webpack-merge');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const common = require('./webpack.common.js');

module.exports = merge(common, {
    output: {
        path: path.resolve(__dirname, './../view/adminhtml/web/js')
    },
    plugins: [
        new UglifyJSPlugin()
    ]
});