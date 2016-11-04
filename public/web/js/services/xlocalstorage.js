app.factory('xlocalstorage', function($localStorage, $http, Logger) {

    var _xlocalstorage = {};
    _xlocalstorage.storageKey = 'storedData';

    _xlocalstorage.mturkLocalStorageRestoreState = function(callback){
        var othis = this;
        if(this.storageKey == 'mTurkStoredData'){
            $http.get(
                "https://wisdomofcrowds.stanford.edu/br_get.php?key=" + 
                $localStorage.workerId
            ).success(function(response){
                if(response['success'] != false){
                    $localStorage[othis.storageKey] = JSON.parse(response['data']); 
                }
                callback();
            }).error(function(error){
                Logger.log(error);
            });
        }
    } 

    _xlocalstorage.setMturkMode = function(mturkMode){
        if(mturkMode){
            this.storageKey = 'mTurkStoredData';
        }else{
            this.storageKey = 'storedData';
        }
    }

    _xlocalstorage.get = function(key){
        if(key == 'workerId'){
            return $localStorage[key];
        }
        if($localStorage[this.storageKey] === undefined){
            $localStorage[this.storageKey] = {
                'roundsCompleted' : 1,
                'beeRank' : 0,
                'beeName' : "Worker Bee"
            };
        }
        
        return $localStorage[this.storageKey][key];
    } 

    _xlocalstorage.set = function(key, value){
        if(key == 'workerId'){
            $localStorage[key] = value;
            return;
        }
        $localStorage[this.storageKey][key] = value;
        if(this.storageKey == 'mTurkStoredData'){
            $http.get(
                "https://wisdomofcrowds.stanford.edu/br_put.php?key=" + 
                $localStorage.workerId +
                '&val=' +
                JSON.stringify($localStorage[this.storageKey])
            ).error(function(response){
                Logger.log(response);
            });
        }
    }

    return _xlocalstorage
});