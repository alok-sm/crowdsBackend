app.controller('quickQuestionsController', function($scope, $timeout, xlocalstorage, $location, Api, Logger, $localStorage) {
    $scope.questions = [0, 1, 2, 3];
    $scope.countries = ["Agriculture", "Architecture", "Biological and Biomedical Sciences", "Business", "Communications and Journalism", "Computer Sciences", "Culinary Arts and Personal Services", "Education", "Engineering", "Legal", "Liberal Arts and Humanities", "Mechanic and Repair Technologies", "Medical and Health Professions", "Other", "Physical Sciences", "Psychology", "Transportation and Distribution", "Visual and Performing Arts"];
    $scope.countryIndex = 0;

    $scope.educationOptions =
        ["Some high school",
        "High school graduate",
        "Some college",
        "Vocational training",
        "College graduate",
        "Some postgraduate work",
        "Post-graduate degree"]

    $scope.challengeDomain = "Guess if business will be funded by Kickstarter";

    if(xlocalstorage.get('currQuestion') === undefined) {
        xlocalstorage.set('currQuestion', 0);
    }
    $scope.currQuestion = xlocalstorage.get('currQuestion');

    if(xlocalstorage.get('user') === undefined) {
        xlocalstorage.set('user', {});
    }
    $scope.user = xlocalstorage.get('user');
    // updateCountries($scope.user.country);

    $scope.setCurrQuestion = function(index) {
        $scope.currQuestion = index;
        xlocalstorage.set('currQuestion', index);
    }

    $scope.countryMouseEnter = function(index) {
        $scope.countryIndex = index;
    }

    $scope.selectCountry = function() {
        var index = $scope.countryIndex;
        if(index < 0 || index >= $scope.countries.length) {
            return;
        }
        $scope.user.country = $scope.countries[index];
    }

    $scope.setGender = function(gender) {
        if(gender === 'male'){
            $scope.user.gender = 'M'
        }else{
            $scope.user.gender = 'F'
        }
        next();
    }

    $scope.next = function() {
        next();
    }

    $scope.prev = function() {
        prev();
    }

    $scope.countryKeydown = function(e) {
        if(e.which === 38 || e.which === 40 || e.which === 13) {
            e.preventDefault();
        }
        // up arrow pressed
        if(e.which === 38 && $scope.countryIndex > 0) {
            $scope.countryIndex--;
        }
        // down arrow pressed
        if(e.which === 40 && $scope.countryIndex < $scope.countries.length - 1) {
            $scope.countryIndex++;
        }
        if(e.which === 13) {
            if(validCountry($scope.user.country)) {
                next();
                return;
            }
            $scope.selectCountry();
        }
    }

    $scope.validInput = function() {
        if($scope.currQuestion === 0) {
            return validGender($scope.user.gender);
        }
        if($scope.currQuestion === 1) {
            return validAge($scope.user.age);
        }
        if($scope.currQuestion === 2) {
            return validCountry($scope.user.country);
        }
        if($scope.currQuestion === 3) {
            return validEducation($scope.user.education);
        }
        if($scope.currQuestion === 4) {
            return validRank($scope.user.rank);
        }
    }

    $scope.validCountry = function(country) {
        return validCountry(country);
    }

    function validGender(gender) {
        if(gender === "M" || gender === "F") {
            return true;
        }
        return false;
    }

    function validAge(age) {
        if(!isInt(age)) {
            return false;
        }
        age = parseInt(age);
        if(age < 1 || age > 200) {
            return false;
        }
        return true;
    }

    function validCountry(country) {
        for(var i = 0; i < $scope.countries.length; i++) {
            if($scope.countries[i] === country) {
                return true;
            }
        }
        return false;
    }

    function validEducation(education) {
        for(var i = 0; i < $scope.educationOptions.length; i++) {
            if($scope.educationOptions[i] === education) {
                return true;
            }
        }
        return false;
    }

    function validRank(rank) {
        if(!isInt(rank)) {
            return false;
        }
        rank = parseInt(rank);
        if(rank < 1 || rank > 100) {
            return false;
        }
        return true;
    }

    function next() {
        if(!$scope.validInput()) {
            return;
        }
        if($scope.currQuestion == $scope.questions.length - 1) {
            var user = {}
            user.gender = xlocalstorage.get('user').gender;
            user.age = xlocalstorage.get('user').age;
            user.education = xlocalstorage.get('user').education;
            user.country = xlocalstorage.get('user').country;

            workerId = xlocalstorage.get('workerId')
            if(workerId){
                user.mturk = workerId;
            }
            
            Api.register(user, function(success){
                var token = success['token'];
                Logger.log('storing mturk id')
                if(token.startsWith('mturk-')){
                    Logger.log(true)
                    $localStorage['mturk-token'] = token;
                }
                xlocalstorage.set('token', token);
                $location.path('challenge/start');
                return;
            }, function(error){
                Logger.log(error);
            });
        }

        $scope.setCurrQuestion($scope.currQuestion + 1);

        if($scope.currQuestion === 1) {
            $timeout(function(){
                $(".quick-questions .age input").focus();
            });
        }

        if($scope.currQuestion === 2) {
            $timeout(function(){
                $(".quick-questions .country input").focus();
            });
        }
    }

    function prev() {
        $scope.setCurrQuestion($scope.currQuestion - 1);
    }

});