var webpack = require('webpack');

var config = {
    entry: {
        'index': "./index.js",
        ///endor: ['react','react-dom','iscroll','jquery']
    },
    output: {
        //publicPath: './static/js',
        path: './assets/js',
        filename: "[name].js",
        chunkFilename: "[name].js"
    },
    devServer: {
        inline: true,
        port: 3008,
        hot: true
    },
    externals: {
        //'react':'React',
        //'react-dom':'ReactDOM',
        //'jquery':"$",
        // 'iscroll':'IScroll'
    },

    resolve: {
        alias: {
            'vue': 'vue/dist/vue.js',
        },
        extensions: ['', '.js', '.vue'],
    },

    plugins: [
        /* new webpack.optimize.CommonsChunkPlugin({
            name:"vendor",  
         }),*/
    ],
    module: {

        loaders: [{
            test: /\.js|\.es6$/,
            exclude: /node_modules/,
            loaders: ['babel-loader']
        }, {
            test: /\.vue$/,
            exclude: /node_modules/,
            loaders: ['vue-loader']
        }, {
            test: /\.(css)$/,
            loader: 'style-loader!css-loader'
        }, {
            test: /\.(eot|svg|ttf|woff|woff2|png)\w*/,
            loader: 'file'
        }]
    },
    babel: {

    }

}

module.exports = config;