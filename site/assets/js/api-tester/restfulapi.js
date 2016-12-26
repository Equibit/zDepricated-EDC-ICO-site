(function (root, factory) {
    if(typeof define === "function" && define.amd) {
        define(["jquery", "cryptojs.core", "cryptojs.x64", "cryptojs.sha512", "cryptojs.hmac", "cryptojs.base64", "cryptojs.pbkdf2"], function($, CryptoJS){
            return (root.restAPI = factory($, CryptoJS));
        });
    } else if(typeof module === "object" && module.exports) {
        module.exports = (root.restAPI = factory(require("jquery", "cryptojs.core", "cryptojs.x64", "cryptojs.sha512", "cryptojs.hmac", "cryptojs.base64", "cryptojs.pbkdf2")));
    } else {
        root.restAPI = factory(root.jQuery);
    }
}(this, function() {
    'use strict';

    var domain = '', authUser = '', authSecret = '';
    var storeDataWith = localStorage;

    domain = (storeDataWith.getItem('domain') ? storeDataWith.getItem('domain') : '' );
    authUser = (storeDataWith.getItem('authUser') ? storeDataWith.getItem('authUser') : '');
    authSecret = (storeDataWith.getItem('authSecret') ? storeDataWith.getItem('authSecret') : '');

    // if not locally stored, check session in case of refresh
    if (authUser == '' && authSecret == '') {
        storeDataWith = sessionStorage;

        domain = (storeDataWith.getItem('domain') ? storeDataWith.getItem('domain') : '' );
        authUser = (storeDataWith.getItem('authUser') ? storeDataWith.getItem('authUser') : '');
        authSecret = (storeDataWith.getItem('authSecret') ? storeDataWith.getItem('authSecret') : '');
    }

    var logoutFunction = null;

    return {
        logout: function () {
            authUser = '';
            authSecret = '';
            sessionStorage.removeItem('authUser');
            sessionStorage.removeItem('authSecret');
            localStorage.removeItem('authUser');
            localStorage.removeItem('authSecret');
        },
        addAuthUser: function (newAuthUser) {
            authUser = newAuthUser;
            storeDataWith.setItem('authUser', authUser);
        },
        switchStoredDataLocally: function () {
            storeDataWith = localStorage;

            storeDataWith.setItem('domain', domain);
            storeDataWith.setItem('authUser', authUser);
            storeDataWith.setItem('authSecret', authSecret);
        },
        switchStoredDataSession: function () {
            storeDataWith = sessionStorage;

            storeDataWith.setItem('domain', domain);
            storeDataWith.setItem('authUser', authUser);
            storeDataWith.setItem('authSecret', authSecret);
            localStorage.removeItem('authUser');
            localStorage.removeItem('authSecret');
        },
        hashMe: function(toHash) {
            return CryptoJS.SHA512(toHash).toString();
        },
        addLogoutFunction: function(func) {
            logoutFunction = func;
        },
        addDomain: function (newDomain) {
            domain = newDomain;
            storeDataWith.setItem('domain', newDomain);
        },
        addSecret: function(newAuthSecret) {
            authSecret = newAuthSecret;
            storeDataWith.setItem('authSecret', newAuthSecret);
        },
        getSecret: function() {
            return authSecret;
        },
        passwordToSecret: function(password) {
            authSecret = this.hashMe(password);
            storeDataWith.setItem('authSecret', authSecret);
        },
        sign: function(type, endPoint, orderedParams, timestamp) {
            var toSign = type + endPoint + orderedParams + timestamp;
            var hash = CryptoJS.HmacSHA512(toSign, authSecret);
            return CryptoJS.enc.Base64.stringify(hash);
        },
        addSecondFactor: function(secondFactor) {
            var hash = CryptoJS.HmacSHA512(authSecret, secondFactor);
            authSecret = CryptoJS.enc.Base64.stringify(hash);
            storeDataWith.setItem('authSecret', authSecret);
        },
        request: function(type, endPoint, paramObj, successFunc, errorFunc) {
            var _self = this;
            if (domain == '' || authUser == '' || authSecret == '') {
                errorFunc({"status":0, "error":"Request not sent, no signature data!"});
                return;
            }
            endPoint = domain + endPoint;
            var orderedParamObj = {};
            if (typeof paramObj !== 'undefined' && type.toUpperCase() != 'DELETE') {
                Object.keys(paramObj)
                    .sort()
                    .forEach(function (v) {
                        orderedParamObj[v] = paramObj[v];
                    });
            };
            var params = this.obj2Params(orderedParamObj);
            var timestamp = Math.round(new Date().getTime() / 1000);
            var signature = this.sign(type, endPoint, params, timestamp);

            $.ajax({
                url: endPoint,
                data: orderedParamObj,
                dataType: 'json',
                headers: {
                    "Auth-Timestamp": timestamp,
                    "Auth-User": authUser,
                    "Auth-Signature": signature,
                    "X-Requested-With": "XMLHttpRequest"
                },
                type: type.toUpperCase(),
                success: function(res, status, xhr) {
                    var newSecret = xhr.getResponseHeader('Auth-Secret');
                    if (typeof newSecret !== 'undefined' && newSecret != '' && newSecret) _self.addSecret(newSecret);

                    var secondFactor = xhr.getResponseHeader('Auth-Second-Factor');
                    if (typeof secondFactor !== 'undefined' && secondFactor != '' && secondFactor) {
                        res.secondFactor = true;
                    }

                    var challenge = xhr.getResponseHeader('Auth-Challenge');
                    var salt = xhr.getResponseHeader('Auth-Salt');
                    if (typeof challenge !== 'undefined' && challenge != '' && challenge && typeof salt !== 'undefined' && salt != '' && salt) {
                        _self.addSecret(CryptoJS.PBKDF2(authSecret, salt, {iterations: 1000, hasher: CryptoJS.algo.SHA512, keySize: 256 / 16}).toString());
                        _self.request('POST', endPoint.replace(domain, ""), {challenge: challenge}, successFunc, errorFunc);
                    } else {
                        if (typeof res.success != 'undefined') successFunc(res.success);
                        else successFunc(res);
                    }
                },
                error: function(xhr){
                    if (xhr.status == 401 && endPoint.indexOf('initiate') == -1 && typeof logoutFunction == 'function') {
                        logoutFunction();
                    }
                    if (typeof errorFunc == 'function') errorFunc(JSON.parse(xhr.responseText));
                }
            });
        },
        requestUnsigned: function(type, endPoint, paramObj, successFunc, errorFunc) {
            if (domain == '') {
                errorFunc();
                return;
            }
            endPoint = domain + endPoint;
            $.ajax({
                url: endPoint,
                data: paramObj,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                type: type.toUpperCase(),
                success: function(res, status, xhr) {
                    var secondFactor = xhr.getResponseHeader('Auth-Second-Factor');
                    if (typeof secondFactor !== 'undefined' && secondFactor != '' && secondFactor) {
                        res.secondFactor = true;
                    }

                    if (typeof res.success != 'undefined') successFunc(res.success);
                    else successFunc(res);
                },
                error: function(xhr){
                    if (typeof errorFunc == 'function') errorFunc(JSON.parse(xhr.responseText));
                }
            });
        },
        obj2Params: function(obj) {
            var str = "";
            for (var key in obj) {
                if (!obj.hasOwnProperty(key)) continue;
                if (obj[key] instanceof Array && obj[key].length === 0){
                    continue;
                }
                if (str != "") str += "&";
                str += key + "=" + obj[key];
            }
            return str;
        }
    };
}));