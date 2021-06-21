const webpack = require('webpack');
const merge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const defaultConfig = require('./webpack.config.js');

module.exports = merge(defaultConfig, {
  mode: 'development',
  devServer: {
    overlay: true,
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
      // chunkFilename: '[id].css'
    }),

    new webpack.SourceMapDevToolPlugin({
      filename: '[file].map',
    }),
  ],
});
