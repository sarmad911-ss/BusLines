function GoogleAuth(client_id) {
    this.client_id = client_id;
    this.GoogleAuth = null;
    this.init = function(){
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://apis.google.com/js/platform.js?onload=googleinit";
            js.async = true;
            js.defer = true;
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'gapi'));
        this.waitLoad();
        return this;
    };
    this.waitLoad = function () {
        let self = this;
        setTimeout(function(){
            if(typeof gapi.load === "undefined")
                self.waitLoad();
            else
                self.onload();
        },500)
    };
    this.onload = function () {
        let self = this;
        gapi.load('auth2', function() {
            gapi.auth2.init({client_id : self.client_id})
            .then(function (GoogleAuth) {
                self.GoogleAuth = GoogleAuth;
            });
        })
    };
    this.login = function(){
        let self = this;
        if(typeof this.GoogleAuth === "undefined")
            return false;
        if(this.GoogleAuth.isSignedIn.get())
            self.onLogin(self.GoogleAuth.currentUser.get().getBasicProfile());
        else{
            this.GoogleAuth.signIn().then(function(){
                self.onLogin(self.GoogleAuth.currentUser.get().getBasicProfile());
            })
        }
        return self;
    };
    this.logout = function(){
        this.GoogleAuth.signOut().then(()=>{
            this.onLogout();
        });
        return this;
    };
    this.onLogin = function(BasicProfile){
        console.log(BasicProfile.getId(), BasicProfile.getName(), BasicProfile.getGivenName(), BasicProfile.getFamilyName(), BasicProfile.getImageUrl(), BasicProfile.getEmail());
    };
    this.onLogout = function () {
        
    }
}

module.exports = GoogleAuth;