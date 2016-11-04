app.controller('challengeTaskController', function($sce,$http, xlocalstorage, $scope, $interval, $timeout, $location, Logger) {

    $scope.validInput = function(answer, selectedCL) {
        if($scope.timeLeft === 0) {
            return true;
        }
        if(!isValidCL(selectedCL)) {
            return false;
        }
        if(!isValidAnswer(answer)) {
            return false;
        }
        return true;
    }

    $scope.setCL = function(cl) {
        $scope.selectedCL = cl;
    }

    $scope.scrollToConfidenceOptions = function() {
        var responseContainer = $(".task .response-scrollable")
        responseContainer.scrollTop(responseContainer.prop("scrollHeight"));
    }

    $scope.hasStats = function() {
        if($scope.previousResponses !== undefined && $scope.previousResponses.length > 0) {
            return true;
        }
        return false;
    }

    $scope.selectAnswer = function(answer) {
        console.log($scope.selectedAnswer);
        $scope.selectedAnswer = answer;
        $scope.scrollToConfidenceOptions();
    }

    $scope.next = function() {
        $(".next-button").button("loading");
        if(!buttonClicked){
            buttonClicked = true;
            next();
        }
    }

    $scope.setCurrQuestion = function(index, response) {
        if(response && response['task']){
            setCurrQuestion(index, response);
        }else{
            $http.get("https://wisdomofcrowds.stanford.edu/api/tasks?token=" + xlocalstorage.get('token'))
            .success(function(response){
                setCurrQuestion(index, response);
            })
            .error(function(){
                Logger.log("something went wrong");
            });
        }
    }

    function isValidCL(cl) {
        var minCL = $scope.confidenceLevels[0];
        var maxCL = $scope.confidenceLevels[$scope.confidenceLevels.length-1];
        if(cl === undefined || !isInt(cl) || cl < minCL || cl > maxCL) {
            return false;
        }
        return true;
    }

    function isValidAnswer(answer) {
        if($scope.answers.selectedAnswer){
            if($scope.question.type === 'text'||$scope.question.type==='int') {
                return true;
            }
        }
        for(var i = 0; i < $scope.answers.length; i++) {
            if($scope.answers[i] === answer) {
                return true;
            }
        }
        return false;
    }

    function next() {
        if($scope.question.type === 'text' || $scope.question.type ==='int'){
            $scope.selectedAnswer = $scope.answers.selectedAnswer;
        }
        var req_params = {
            "token"      : xlocalstorage.get('token'),
            "time_taken" : $scope.timeLeft,
            "confidence" : ""+(parseInt($scope.selectedCL) + 1),
            "data"       : $scope.selectedAnswer,
            "task_id"    : $scope.question.taskId
        }

        console.log($scope.selectedAnswer);
        console.log(req_params);
        $http.post("https://wisdomofcrowds.stanford.edu/api/answers", req_params)
        .success(function(response){
            console.log("Successful submission");
            $scope.setCurrQuestion($scope.currQuestion + 1, response);
            buttonClicked = false;
            $timeout(function(){
                $(".next-button").button("reset");
            }, 300);
        })
        .error(function(response) {
            console.log("error in submission");
            $timeout(function(){
                $(".next-button").button("reset");
            }, 300);
        });
    }

    function setCurrQuestion(index, response) {
        if(index === 20) {
            $location.path("challenge/done");
            xlocalstorage.set('roundsCompleted', 
                xlocalstorage.get('roundsCompleted') + 1);
            return;
        }

        xlocalstorage.set('answer_type', response.task.answer_type);

        Logger.log(response);
        $scope.question = {};
        $scope.question.questionType = response.task.type;
        $scope.question.taskId = response.task.id;
        $scope.question.text = response.task.title;
        $scope.question.type = response.task.answer_type;
        $scope.question.data = response.task.data;
        $scope.question.experimentalCondition = parseInt(response.experimental_condition);

        // if($scope.question.experimentalCondition == 1){
        //     $scope.question.experimentalCondition = 0;
        //     // temporarily disabled condtion 1. Re-enable before deplyoment
        // }

        timeAvailable = response.timeout;
        $scope.currQuestion = 20 - response.remaining - 1;
        Logger.log('currQuestion: ' + $scope.currQuestion)
        $scope.answers = response.task.answer_data.split(",");

        if($scope.question.questionType == "image"){
                $scope.question.data = "https://dzpz27bktbdd8.cloudfront.net/" + 
                    $scope.question.data.split('/').splice(3).join('/');
        }
        if($scope.question.questionType == "video" || $scope.question.questionType == "audio" ){
                $scope.question.data = $sce.trustAsResourceUrl($scope.question.data);
        }
        Logger.log($scope.answers);
        // $scope.question = response;

        $scope.previousResponses = [];
        if(($scope.question.experimentalCondition!==0)&&(response.stats!=="Not enough data"))
        {
            console.log("Experimental Condition detected");
            $scope.showMedian = false;
            switch($scope.question.experimentalCondition)
            {
                case 1:
                    console.log("Experimental Condition 1");
                    if($scope.question.type==='int'){
                        $scope.question.experimentalConditionTitle = "Median answer";
                        $scope.median = response.stats.data;
                        $scope.previousResponses = [1,2,3];
                        Logger.log($scope.previousResponses)
                        $scope.showMedian = true;
                    }else{
                        // slice is a hack to put only the first 3 social condition entries on the front end
                        for(var kkey in response.stats){
                            var attrName = kkey;
                            var attrValue = response.stats[kkey];
                            // var eentry = {text: attrName, count: parseInt(attrValue)};
                            var eentry = attrName + ": " + attrValue + " time(s)"
                            $scope.previousResponses.push(eentry);
                            console.log(attrName+" "+attrValue);
                        }
                        // updatePreviousResponseCount();
                        $scope.question.experimentalConditionTitle = "Most popular responses";
                    }
                break;

                case 2:
                    console.log("Experimental Condition 2");
                    $scope.previousResponses = response.stats;
                    $scope.question.experimentalConditionTitle = "Most recent responses";
                break;

                case 3:
                    console.log("Experimental Condition 3");
                    $scope.previousResponses = response.stats;
                    $scope.question.experimentalConditionTitle = "Most early responses";
                break;

                case 4:
                    console.log("Experimental Condition 4");
                    $scope.previousResponses = response.stats;
                    $scope.question.experimentalConditionTitle = "Responses from the most confident people";
                break;
            }
            console.log($scope.previousResponses);
        }

        $scope.selectedAnswer = undefined;
        $scope.selectedCL = undefined;

        switchSlides();
        resetTimer();
        
    }

    function switchSlides() {
        var duration = 400;
        if($scope.slide_1) {
            $scope.slide_1 = false;
            $timeout(function(){
                $scope.slide_2 = true;
            }, duration);
        }
        if($scope.slide_2) {
            $scope.slide_2 = false;
            $timeout(function(){
                $scope.slide_1 = true;
            }, duration);
        }
    }

    function initTimer() {
        $scope.timerOptions = {
            animate:{
                duration:300,
                enabled:true
            },
            barColor: "#feeed9",
            scaleColor:false,
            trackColor: "#f7a32b",
            lineWidth:timerLineWidth,
            lineCap:"circle",
            size: timerSize
        };
    }

    function startTimer() {
        //return;

        $interval(function(){
            if($scope.timeLeft === 0) {
                return;
            }
            $scope.timeLeft--;
            $scope.timerPercent += 100/timeAvailable;
        }, 1000);
    }

    function resetTimer() {
        $scope.timeLeft = timeAvailable;
        $scope.timerPercent = 0;
    }

    $scope.currentSlide = 1;
    $scope.slide_1 = true;
    $scope.slide_2 = false;

    $scope.selectedAnswer = undefined;
    $scope.selectedCL = undefined;
    $scope.confidenceLevels = numberArray(0, 4);
    $scope.answers = [];

    var timerSize = 50;
    var timerLineWidth = 6;
    var buttonClicked = false;

    // make xlocalstorage directly accessible in view
    $scope.xlocalstorage = xlocalstorage;

    // custom size styling for large devices (large desktops, 1280px and up)
    if($(document).height() > 960) {
        timerLineWidth = 8;
        timerSize = 80;
    }
    var timeAvailable = 30;

    $scope.questions = numberArray(0, 19);
    $scope.setCurrQuestion(0, null);

    initTimer();
    resetTimer();
    startTimer();
});
