/*
In general, api functions follow this structure: (data, success callback function, error callback function)
*/

app.factory('WisdomApi', function($http, Logger) {
    var api = {};
    var url = "https://wisdomofcrowds.stanford.edu/api";
    // expects user to have fields: gender, education, employment, age
    // returns token if successful
    api.register = function(user, success, error) {
        post(url + "/users", user, success, error);
    }

    // expects data to have fields: token, rank
    api.submitExpectedRank = function(data, success, error) {
        get("https://wisdomofcrowds.stanford.edu/user_domain_rank.php?user_id=" + data.token + "&domain_id=" + data.domain_id + "&rank=" + data.rank, 
            function(){
                Logger.log('sent self rankx')
                post(url + "/answers", data, success, error);
            }, 
        error);
        
    }

    function post(url, data, success, error) {
        $http.post(url, data)
            .success(function(response) {
                success(response);
            })
            .error(function(response) {
                error(response);
                Logger.log(response);
            });
    }

    function get(url, success, error) {
        $http.get(url)
            .success(function(response) {
                success(response);
            })
            .error(function(response) {
                error(response);
                Logger.log(response);
            });
    }

    return api;


});