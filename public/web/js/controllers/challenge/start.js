app.controller('challengeStartController', function($http, $scope, $location, $sce, xlocalstorage, Api, Logger) {
    $scope.user = {};
    $http.get("https://wisdomofcrowds.stanford.edu/api/tasks?token=" + xlocalstorage.get('token'))
    .success(function(response){
        if(response.status=="done"){
            $location.path('challenge/completed');
        }
        if(response.domain !== undefined) {
            $scope.domainInfo = $sce.trustAsHtml(response.domain.name);
            xlocalstorage.set('domainId', response.domain.id);
            xlocalstorage.set('domainInfo', response.domain.name);
        }
        if(response.domain === undefined && xlocalstorage.get('domainInfo') !== undefined) {
            $scope.domainInfo = $sce.trustAsHtml(xlocalstorage.get('domainInfo'));
        }


        if(response.experimental_condition){
            $location.path('challenge/task');
        }

        xlocalstorage.set('totalQuestions', response.remaining);
        Logger.log(response);
        console.log(response);
        $scope.prev = function() {
            $location.path('user/terms-and-conditions');
        }
        $scope.validInput = function() {
            if(validRank($scope.user.rank)) {
                return true;
            }
            return false;
        }

        $scope.startChallenge = function(e) {
            $(e.target).button('loading');
            if(!$scope.validInput()) {
                return;
            }
            var data = {};
            data.token = xlocalstorage.get('token');
            data.rank = $scope.user.rank;
            data.domain_id = xlocalstorage.get('domainId');
            Api.submitExpectedRank(data, function(success){
                $location.path('challenge/task');
            }, function(error){
                alert(error);
            });
        }

        function validRank(rank) {
            // Logger.log(rank);
            rank = parseInt(rank);
            // if(!isInt(rank)) {
            //     return false;
            // }
            // if(rank < 1 || rank > 100) {
            //     return false;
            // }
            // return true;
            return(rank > 0 && rank <=100 && isInt(rank)) 
        }
    })
    .error(function(error){
        Logger.log(error);
    });
});