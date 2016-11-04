app.controller('introWelcomeController', function($http, $scope, $location, $interval, Logger, xlocalstorage, $localStorage) {
	
	var beeNameMonitor = $interval(function() {
		// Logger.log("Let's start, " + xlocalstorage.get('beeName') + "!")
		$scope.nextButtonText = "Let's start, " + xlocalstorage.get('beeName') + "!"
	}, 100);

	$scope.waitForHitAccept = false;

	if($location.search().assignmentId){
		xlocalstorage.set('workerId', $location.search().workerId);
		xlocalstorage.setMturkMode(true);
		xlocalstorage.mturkLocalStorageRestoreState(function(){
			xlocalstorage.set('assignmentId', $location.search().assignmentId);
			xlocalstorage.set('hitId', $location.search().hitId);
			xlocalstorage.set('turkSubmitTo', $location.search().turkSubmitTo);
			
			$scope.waitForHitAccept = $location.search().hitId != undefined &&
					xlocalstorage.get('assignmentId') == "ASSIGNMENT_ID_NOT_AVAILABLE";

			var mturkToken = $localStorage['mturk-token'];
			
			if(mturkToken){
				$http.get("https://wisdomofcrowds.stanford.edu/api/tasks?token=" + mturkToken)
		        .success(function(response){
		        	Logger.log('finish hit check');
		        	Logger.log(response)
		        	if(response.status == "done"){
			            $location.path('challenge/completed');
			        }
			    })
			    .error(function(error){
			    	Logger.log(error)
			    });
			}
		});
	}else{
		xlocalstorage.setMturkMode(false);
	}

	

	Logger.log($scope.waitForHitAccept)

    $scope.start = function() {
    	$scope.stateRestored = true;
    	$scope.nextButtonText = "Loading..."
    	$interval.cancel(beeNameMonitor);
    	beeNameMonitor = null;
        var token = xlocalstorage.get('token');
        if(token !== undefined && token !== null && token.length > 0) {

		


            $http.get("https://wisdomofcrowds.stanford.edu/api/tasks?token=" + xlocalstorage.get('token'))
            .success(function(response){
            	if(response.experimental_condition){
                    $location.path('challenge/task');
                }else{
                    $location.path('challenge/start');
                }
            })
            .error(function(){
                Logger.log(error)
            });
            return;
        }
        $location.path('intro/explanation');
    }
});