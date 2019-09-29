class Router {
    constructor(){
        this.routes = {}
    }
    get(route){
        let self = this;
        return new Promise(function (resolve,reject) {
            let res = resolve;
            if(self.routes[route])
                resolve(self.routes[route]);
            else {
                self.getRoutes([route]).then(function (routes) {
                    Object.assign(self.routes,routes);
                    res(self.routes[route]);
                }).catch(({data})=>{
                    reject(data);
                })
            }
        });
    }
    getRoutes(routes){
        let self = this;
        return new Promise(function (resolve,reject) {
            axios.post("/api/routes",{'routes':routes}).then(({data})=>{
                resolve(data.data.routes);
            }).catch(({data})=>{
                reject(data);
            });
        });
    }
}

module.exports = new Router();