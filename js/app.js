/* global angular */
var app = angular.module("chadrickApp", []);

app.controller("mainCtrl", function($scope, $interval, $http) {

    window.vm = $scope;

    var instance = this;

    $scope.CurrentName = "Loading...";    
    $scope.momentDemotionDate = moment().add(1, 'd');    
    $scope.momentResetDate = moment().add(3, 'd');
    $scope.addBurnData = {};
    $scope.BurnFeed = [];

    this.$onInit = function() {
        $scope.refreshState();
        $scope.loadBurns();
    };

    $scope.refreshState = function() {
        $scope.loading = true;
        $http.get("Actions/GetCurrentState.php")
            .then(function(response) {
                //console.log(response.data);
                $scope.loading = false;
                $scope.CurrentName = response.data.CurrentName;
                $scope.momentDemotionDate = moment(response.data.ClockStart24h).add(1, 'd');                
                $scope.momentResetDate = moment(response.data.ClockStart72h).add(3, 'd');
            });
    };

    $scope.loadBurns = function() {

        var lastBurnId = "";
        if ($scope.BurnFeed.length !== 0) {
            lastBurnId = $scope.BurnFeed[$scope.BurnFeed.length - 1];
            lastBurnId = lastBurnId.Id;
        }

        //console.log("Actions/GetBurns.php?LastBurnId=" + lastBurnId);

        $http.get("Actions/GetBurns.php?LastBurnId=" + lastBurnId)
            .then(function(response) {
                //console.log(response.data);
                $scope.BurnFeed = $scope.BurnFeed.concat(response.data);
            });
    };

    $scope.postBurn = function(e) {

        if (!$scope.postBurnForm.$valid) {
            alert("All fields are required.")
            return;
        }

        $scope.loading = true;
        $scope.updateState($scope.addBurnData);
        $http.post("Actions/PostBurn.php", $scope.addBurnData)
            .then(function(response) {
                //console.log(response);
                $scope.loading = false;
                $scope.addBurnData = {};
                $scope.BurnFeed = [];
                instance.$onInit();
            });
    };

    $scope.updateState = function(newBurn) {
        $scope.loading = true;
        $http.post("Actions/UpdateCurrentState.php", newBurn)
            .then(function(response) {
                //console.log(response);
                $scope.loading = false;
                $scope.refreshState();
            });
    }

    $interval(function() {
        var demotionDuration = moment.duration($scope.momentDemotionDate.diff(moment().add(4, 'h')));
        $scope.momentDemotionTimer = demotionDuration.hours() + 'h ' + demotionDuration.minutes() + 'm ' + demotionDuration.seconds() + 's';        
        $scope.LowDemotionCountDown = demotionDuration.hours() === 0;     
        var resetDuration = moment.duration($scope.momentResetDate.diff(moment().add(4, 'h')));        
        $scope.momentResetTimer = resetDuration.days() + 'd ' + resetDuration.hours() + 'h ' + resetDuration.minutes() + 'm ' + resetDuration.seconds() + 's'
        $scope.LowResetCountDown = resetDuration.hours() === 0 && resetDuration.days() === 0;
    }, 500);

    $interval(function() {
        $scope.updateState({}); //every two minutes update and refresh state
    }, 30000);

});
