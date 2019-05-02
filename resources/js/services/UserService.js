export default class UserService {

    constructor(httpService) {
        this.httpService = httpService;
        this.route = "/users";
    }

    me() {
        const route = "/me"
        return this.httpService.makeGet(route)
            .then(res => {
                return Promise.resolve(res);
            })
            .catch(err => {
                return Promise.reject(err);
            });
    }

}
