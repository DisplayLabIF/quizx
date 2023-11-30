var Encore = require("@symfony/webpack-encore");
var dotenv = require('dotenv');

// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
.copyFiles([
  {from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
  {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
  {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
  {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
  {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'},
  {from: './assets/images', to: 'images/[path][name].[ext]', pattern: /\.(png|jpg|jpeg|svg)$/}
])
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  .enableReactPreset()
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  /*
   * ENTRY CONFIG
   *
   * Add 1 entry for each "page" of your app
   * (including one that's included on every page - e.g. "app")
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
   */
  .addEntry("site", "./assets/js/site.js")
  .addEntry("app", "./assets/js/app.js")
  .addEntry("app-quiz", "./assets/js/app-quiz.js")
  .addEntry("sortable", "./assets/js/app/jquery-sortable.js")
  .addEntry("nivelamento-configuracao", "./assets/js/app/nivelamento/configuracao.js")
  .addEntry("daterangepicker", "./assets/js/daterangepicker.js")  
  .addEntry("camposConfiguracaoQuiz-dragAndDrop", "./assets/js/app/quiz/camposConfiguracaoQuiz-dragAndDrop.js")
  .addEntry("camposConfiguracaoNivelamento-dragAndDrop", "./assets/js/app/nivelamento/camposConfiguracao-dragAndDrop.js")
  .addEntry("chart-js", "./assets/js/chart.js")  


  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  // enables @babel/preset-env polyfills
  .configureBabel(() => {}, {
    useBuiltIns: "usage",
    corejs: 3,
  })

    .enableSassLoader()
    .configureDefinePlugin(options => {
        const env = dotenv.config();

        if (env.error) {
            throw env.error;
        }

        options['process.env'].QUIZCLASS_API = JSON.stringify(env.parsed.QUIZCLASS_API);
        options['process.env'].TOKEN_VIMEO = JSON.stringify(env.parsed.TOKEN_VIMEO);
        options['process.env'].SOCKET = JSON.stringify(env.parsed.SOCKET);
    })
;

module.exports = Encore.getWebpackConfig();
