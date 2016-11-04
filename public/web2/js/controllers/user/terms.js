app.controller('termsController', function($scope, $location, xlocalstorage, Logger) {
    $scope.prev = function() {
        $location.path('user/quick-questions');
    }

    $scope.startChallenge = function(e) {
        $(e.target).button("loading");
        $location.path('user/quick-questions');
    }

    $scope.onMTurk = (xlocalstorage.get('assignmentId') != undefined);
});