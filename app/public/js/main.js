document.addEventListener("DOMContentLoaded", (event) => {
    const enter = document.getElementById("enter");
    const exit = document.getElementById("exit");

    if (!!enter) {
        enter.onmousedown = (e) => submitAuthForm(e);
    }
    if (!!exit) {
        exit.onmousedown = (e) => logout(e);
    }
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
        .then(() => {location.reload()})
        .catch(error => showMsg(error));
}
//        .then(result => showResult(result))
const logout = (e) => {
    return new Promise((resolve, reject) => {
        resolve(sendRequest('GET', '/logout'));
    })
        .then(() => {location.reload()})
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
    if (!response.ok) {
        throw new Error(json.message);
    }
    return json;
}

const showResult = (result) => {

}

const showMsg = (msg) => {
    const label = document.getElementById('msg-label');
    if ((String(msg).length) > 0) {
        label.innerHTML = msg;
        label.style.setProperty('display', 'block', 'important');

        setTimeout(() => {
            label.style.setProperty('display', 'none', 'important');
        }, 2500);
    }}