app.controller('challengeDoneController', function($sce, $scope, $location, xlocalstorage, $http, Logger) {
   $scope.numCorrect = 16;
   $scope.numQuestions = 20;
   $scope.rank = 40;
   $scope.numBees = 60;
    $http.get("https://wisdomofcrowds.stanford.edu/api/rank?token=" + xlocalstorage.get('token'))
    .success(function(response){
            $scope.numCorrect = response.points;
            $scope.numBees = response.total_users;
            $scope.rank = response.rank;
			$scope.crowdRank = response.crowd_rank;
            //$scope.domainInfo = response.domain.name;
            //xlocalstorage.domainId = response.domain.id;
    })
    .error(function(error)
    {
        console.log(error);
    });
    var beeLevelStages = [
        {roundsNeeded: 0, name: "Worker Bee"},
        {roundsNeeded: 1, name: "Buzzer"},
        {roundsNeeded: 2, name: "Stinger"},
        {roundsNeeded: 3, name: "Forager"},
        {roundsNeeded: 4, name: "Waggle Dancer"},
        {roundsNeeded: 5, name: "Hive Builder"},
        {roundsNeeded: 6, name: "Hive Fanner"},
        {roundsNeeded: 7, name: "Honey Extractor"},
        {roundsNeeded: 8, name: "Pollinator"},
        {roundsNeeded: 9, name: "Babysitter"},
        {roundsNeeded: 10, name: "Queen Bee"}]

    updateBeeLevelProgress();

	//$scope.numCorrect = 16;
   // $scope.numQuestions = 20;
   // $scope.rank = 40;
   // $scope.crowdRank = 320;
   // $scope.numBees = 3942;

    $scope.startNewChallenge = function() {
        $location.path('challenge/start');
    }

    $scope.onMTurk = (xlocalstorage.get('assignmentId') != undefined);
    Logger.log(xlocalstorage.get('turkSubmitTo') + "/externalSubmit");
    
    $scope.submitURL = $sce.trustAsResourceUrl(xlocalstorage.get('turkSubmitTo') + "/mturk/externalSubmit");
    Logger.log($scope.submitURL)
    $scope.assignmentId = xlocalstorage.get('assignmentId');
    Logger.log(xlocalstorage.get('assignmentId'));

    function updateBeeLevelProgress() {
        var roundsCompleted = xlocalstorage.get('roundsCompleted');
        var beeRank = 0;
        for(var i = 0; i < beeLevelStages.length - 1; i++) {
            if(beeLevelStages[i+1].roundsNeeded > roundsCompleted) {
                beeRank = i;
                xlocalstorage.set('beeName', beeLevelStages[i].name);
                break;
            }
        }
        if(roundsCompleted === 50) {
            beeRank = 10;
            xlocalstorage.set('beeName', "Queen Bee");
        }
        xlocalstorage.set('beeRank', beeRank);
        $scope.roundsToUnlockNextBee = 0;
        $scope.beeWasUnlocked = false;
        $scope.hasNextBeeToUnlock = true;
        if(roundsCompleted === 50) {
            $scope.hasNextBeeToUnlock = false;
        }
        if(beeLevelStages[beeRank].roundsNeeded === roundsCompleted) {
            $scope.beeWasUnlocked = true;
            return;
        }
        if(roundsCompleted < 50) {
            $scope.roundsToUnlockNextBee = beeLevelStages[beeRank+1].roundsNeeded - roundsCompleted;
        }
    }

    $scope.setRoundsCompleted = function(rounds) {
        xlocalstorage.set('roundsCompleted', rounds);
        updateBeeLevelProgress();
    }

    $scope.showScore = (xlocalstorage.get('answer_type') == 'int');
});
