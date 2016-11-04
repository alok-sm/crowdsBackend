app.controller('mainController', function($scope, SITE_NAME, xlocalstorage) {
	$scope.SITE_NAME = SITE_NAME;
    $scope.xlocalstorage = xlocalstorage;

    if(xlocalstorage.get('roundsCompleted') === undefined) {
        xlocalstorage.set('roundsCompleted', 1);
    }
    if(xlocalstorage.get('beeRank') === undefined) {
        xlocalstorage.set('beeRank', 0);
    }
    if(xlocalstorage.get('beeName') === undefined) {
        xlocalstorage.set('beeName', "Worker Bee");
    }

    $scope.animatedBeePath = function() {
        return "https://dzpz27bktbdd8.cloudfront.net/img/bee-animations/" + xlocalstorage.get('beeRank') + ".gif";
    }
    $scope.beePath = function() {
        return "https://dzpz27bktbdd8.cloudfront.net/img/bees/" + xlocalstorage.get('beeRank') + ".png";
    }
    $scope.rankBarBeePath = function() {
        return "https://dzpz27bktbdd8.cloudfront.net/img/bees-for-rank-bar/" + xlocalstorage.get('beeRank') + ".png";
    }
    $scope.achievementImagePath = function() {
        return "https://dzpz27bktbdd8.cloudfront.net/img/achievements/" + xlocalstorage.get('beeRank') + ".png";
    }

    $scope.ordinal = function(i) {
        var j = i % 10,
            k = i % 100;
        if (j == 1 && k != 11) {
            return "st";
        }
        if (j == 2 && k != 12) {
            return "nd";
        }
        if (j == 3 && k != 13) {
            return "rd";
        }
        return "th";
    }
});