function FBAuth(client_id) {
    this.client_id = client_id;
    this.init = function () {
        var _this = this;
        window.fbAsyncInit = function () {
            FB.init({
                appId: _this.client_id,
                cookie: true,
                xfbml: true,
                version: 'v3.2'
            });
            FB.AppEvents.logPageView();
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        return this;
    };
    this.login = function (){
        var _this = this;
        FB.getLoginStatus(function(response) {
            _this.onLoginStatus(response);
        });
        return this;
    };
    this.onLoginStatus = function(response) {
        var _this = this;
        if(response.status==="connected"){
            this.getUserData();
        }else{
            FB.login(function(response) {
                _this.onLoginStatus(response);
            }, {scope: 'public_profile,email'});
        }
        return this;
    };
    this.onLogin = function (response) {
        console.log(response);
    };
    this.logout = function () {
        let self = this;
        FB.getLoginStatus(function(response) {
            if(response.status==="connected")
                FB.logout(function (response) {
                    self.onLogout(response);
                });
        });
    };
    this.onLogout = function (response) {

    };
    this.getUserData = function(){
        FB.api('/me', {fields: 'first_name,last_name,email'}, (response) => {
            this.onLogin(response);
        });
    }
}

module.exports = FBAuth;