$(function() {
    restAPI.addDomain('http://easyappframework.com');

    $('.logout').click(function(){
       restAPI.logout();
    });

    $('#saveKey').click(function() {
        var public = $('#public').val();
        var secret = $('#secret').val();

        restAPI.addAuthKey(public);
        restAPI.addAPISecret(secret);

        console.log('stuff saved! ' + public + ' ' + secret);
    });

    $('.button').click(function(){
        var url = $(this).data('url');
        var method = $(this).data('method');
        var resultsID = $(this).data('results');
        var rawData = $(this).data('api-data');
        var data;
        if (typeof rawData !== 'undefined') {
            data = JSON.parse($('#' + rawData).val());
            for (var key in data) {
                if (!data.hasOwnProperty(key)) continue;

                if (key == 'loginUserName') {
                    restAPI.addAuthUser(data[key]);
                    data[key] = null;
                }
                if (key == 'loginPassword') {
                    restAPI.passwordToSecret(data[key]);
                    data[key] = null;
                    data['password'] = restAPI.getSecret();
                }
                if (key == 'password' || key == 'oldPassword' || key == 'newPassword' || key == 'pass' || key == 'retype') {
                    data[key] = restAPI.hashMe(data[key]);
                }
            }
        } else {
            data = {};
        }
        var extraVar = $(this).data('url-data');
        if (typeof extraVar !== 'undefined') {
            var value = $('#' + extraVar).val();
            if (value != '') url = url + value + '/';
        }

        if (typeof $(this).data('api') != 'undefined') {
            restAPI.requestAPI(method, url, data,
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                },
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                });
        } else if (typeof $(this).data('unsigned') == 'undefined') {
            restAPI.request(method, url, data,
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                },
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                });
        } else {
            restAPI.requestUnsigned(method, url, data,
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                },
                function (res) {
                    $('#' + resultsID).val(JSON.stringify(res));
                });
        }
    });
});