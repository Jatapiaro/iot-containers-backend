export default class HttpService {

    makeGet(route) {
        const endpoint = this.getEnpoint(route);
        return window.axios.get(endpoint).then(res => {
            return res.data.data
        })
        .catch(err => {
            return Promise.reject(err.response.data);
        });
    }

    makePost(route, body, isFormData = false) {
        const endpoint = this.getEnpoint(route);
        const headers = this.getHeaders(isFormData);
        return window.axios.post(endpoint, body, {headers: headers}).then(res => {
            return res.data.data;
        })
        .catch(err => {
            return Promise.reject(err.response.data);
        });
    }

    makePut(route, body) {
        const endpoint = this.getEnpoint(route);
        const headers = this.getHeaders(false);
        return window.axios.put(endpoint, body, { headers: headers }).then(res => {
            return res.data.data;
        })
        .catch(err => {
            return Promise.reject(err.response.data);
        });
    }

    makeDelete(route, body = {}) {
        const endpoint = this.getEnpoint(route);
        const headers = this.getHeaders(false);
        return window.axios.delete(endpoint, body, { headers: headers }).then(res => {
            return Promise.resolve(res.data.data);
        })
        .catch(err => {
            return Promise.reject(err.response.data);
        });
    }

    getHeaders(isFormData) {
        const headers = {
            'Content-Type': (isFormData) ? 'multipart/form-data' : 'application/json',
            'Accept': 'application/json'
        }
        return headers;
    }

    getEnpoint(route) {
        return `${window.baseUrl}/api/v1${route}`;
    }

}
