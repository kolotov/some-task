document.addEventListener("DOMContentLoaded", (event) => {
    const enter = document.getElementById("enter");
    const exit = document.getElementById("exit");
    const update = document.getElementById("update");

    if (!!enter) {
        enter.onmousedown = (e) => submitAuthForm(e);
    }
    if (!!exit) {
        exit.onmousedown = (e) => logout(e);
    }
    if (!!update) {
        update.onmousedown = (e) => incrementCounter(e);
    }

    updateCounter();
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

const logout = (e) => {
    return new Promise((resolve, reject) => {
        resolve(sendRequest('GET', '/logout'));
    })
        .then(() => {location.reload()})
        .catch(error => showMsg(error));
}

const incrementCounter = (e) => {

    return new Promise((resolve, reject) => {
        resolve(sendRequest('PUT', '/increment'));
    })
        .then(() => updateCounter())
        .catch(error => showMsg(error));
}


const updateCounter = () => sendRequest('GET', '/increment')
.then((result) => document.getElementById("counter").innerHTML = result.toString())

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
    return json.result;
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

