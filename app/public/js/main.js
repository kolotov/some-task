document.addEventListener("DOMContentLoaded", (event) => {

    document.getElementById("enter").onmousedown = (e) => submitAuthForm(e);
});

/**
 * Submit form
 * @param e
 */
const submitAuthForm = (e) => {
    return new Promise((resolve, reject) => {
        let data = {};
        data.username = document.getElementById("username").value;
        data.password = document.getElementById("password").value;

        resolve(sendRequest('POST', '/auth', data));
    })
        .then(result => showResult(result))
        .catch(error => showMsg(error));
}

/**
 * Send Request
 * @param method
 * @param url
 * @param data
 * @returns {Promise<any>}
 */
const sendRequest = async(method, url, data = {}) => {
    const headers = {'Content-Type': 'application/json; charset=utf-8'};
    const params = {
        PUT: {
            method: 'PUT',
            body: JSON.stringify(data),
            headers: headers
        },
        POST: {
            method: 'POST',
            body: JSON.stringify(data),
            headers: headers
        },
        GET: {
            method: 'GET',
            headers: headers
        }

    };

    const response = await fetch(url, params[method]);
    const json = await response.json();

    if (response.ok === false) {
        throw new Error(json.error.message);
    }

    if (method === 'GET') {
        return json;
    }
}

const showResult = (result) => {

}

const showMsg = (error) => {

}