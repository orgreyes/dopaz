const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
  mode: 'development',
  watch: true,
  entry: {
    'js/app' : './src/js/app.js',
    'js/inicio' : './src/js/inicio.js',
    'js/evaluaciones/index' : './src/js/evaluaciones/index.js',
    'js/puestos/index' : './src/js/puestos/index.js',
    'js/usuarios/index' : './src/js/usuarios/index.js',
    'js/aspirantes/index' : './src/js/aspirantes/index.js',
    'js/contingentes/index' : './src/js/contingentes/index.js',
    'js/misiones/index' : './src/js/misiones/index.js',
    'js/asigmisiones/index' : './src/js/asigmisiones/index.js',
    'js/ingresos/index' : './src/js/ingresos/index.js',
    'js/resultados/index' : './src/js/resultados/index.js',
    'js/requisitos/index' : './src/js/requisitos/index.js',
    'js/asigrequisitos/index' : './src/js/asigrequisitos/index.js',
    'js/asiggrados/index' : './src/js/asiggrados/index.js',
    'js/asigevaluaciones/index' : './src/js/asigevaluaciones/index.js',


  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  plugins: [
    new MiniCssExtractPlugin({
        filename: 'styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader
            },
            'css-loader',
            'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        loader: 'file-loader',
        options: {
           name: 'img/[name].[hash:7].[ext]'
        }
      },
    ]
  }
};