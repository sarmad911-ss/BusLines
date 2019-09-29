const mix = require('laravel-mix');

mix.extend('vueConfig', new class {
    webpackConfig(webpackConfig) {
        webpackConfig.resolve.extensions.push('.js', '.vue', '.json'); // you don't need this on v4
        webpackConfig.resolve.alias = {
            'vue$': 'vue/dist/vue.esm.js',
            '@vue': __dirname + '/resources/assets/vue'
        };
    }
});
mix.vueConfig();
// mix.browserSync(process.env.MIX_PROXY);
mix.disableNotifications();

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/vue/pages/roadmap.js',"public/pages/roadmap.js");

mix.js('resources/assets/vue/pages/settings.js',"public/pages/settings.js");

mix.js('resources/assets/vue/pages/usersList.js',"public/pages/users/list.js");
mix.js('resources/assets/vue/pages/userProfile.js',"public/pages/users/profile.js");

mix.js('resources/assets/vue/pages/login.js',"public/pages/auth/login.js");
mix.js('resources/assets/vue/pages/changePassword.js',"public/pages/auth/changePassword.js");

mix.js('resources/assets/vue/pages/busList.js',"public/pages/bus/list.js");
mix.js('resources/assets/vue/pages/busStore.js','public/pages/bus/store.js');
mix.js('resources/assets/vue/pages/busProfile.js','public/pages/bus/profile.js');

mix.js('resources/assets/vue/pages/tripsList.js','public/pages/tripsList.js');

mix.js('resources/assets/vue/pages/finances.js','public/pages/finances.js');

mix.js('resources/assets/vue/pages/calculationsList.js','public/pages/calculationsList.js');

mix.js('resources/assets/vue/pages/tripStore.js','public/pages/trip/store.js');
mix.js('resources/assets/vue/pages/tripProfile.js','public/pages/trip/profile.js');

mix.sass("resources/assets/sass/userModule/auth.scss",'public/css/auth.css');
mix.sass("resources/assets/sass/userModule/userslist.scss",'public/css/userslist.css');
mix.sass("resources/assets/sass/userModule/userProfile.scss",'public/css/userProfile.css');
mix.sass("resources/assets/sass/userModule/settings.scss",'public/css/settings.css');
mix.sass("resources/assets/sass/userModule/buslist.scss",'public/css/buslist.css');
mix.sass("resources/assets/sass/userModule/busProfile.scss",'public/css/busProfile.css');
mix.sass("resources/assets/sass/userModule/finances.scss",'public/css/finances.css');

mix.sass("resources/assets/sass/userModule/tripsList.scss",'public/css/tripsList.css');

mix.sass("resources/assets/sass/userModule/tripStore.scss",'public/css/tripStore.css');
mix.sass("resources/assets/sass/userModule/tripProfile.scss",'public/css/tripProfile.css');

mix.sass("resources/assets/sass/loader.scss",'public/css/loader.css');
