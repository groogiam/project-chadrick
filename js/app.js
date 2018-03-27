/* global angular */

Date.prototype.addDays = function(days) {
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

Date.prototype.toCountDownString = function() {
    // Get todays date and time
    var now = new Date().getTime();

    // Find the distance between now an the count down date
    var distance = this - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    return days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
}

var app = angular.module("chadrickApp", []);

var demotionDate = new Date().addDays(1);

app.controller("mainCtrl", function($scope, $interval, $http) {

    var instance = this;

    $scope.CurrentName = "Loading...";
    $scope.demotionDate = new Date().addDays(1);
    $scope.resetDate = new Date().addDays(3);
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
                $scope.demotionDate = new Date(response.data.ClockStart24h + "Z").addDays(1);
                $scope.resetDate = new Date(response.data.ClockStart72h + "Z").addDays(3);
            });
    };

    $scope.loadBurns = function() {

        var maxBurnId = 0;
        
        for (var i = 0; i < $scope.BurnFeed.length; i++) {
            var burn = $scope.BurnFeed[i];
            if (burn.Id > maxBurnId) {
                maxBurnId = burn.Id;
            }
        }

        $http.get("Actions/GetBurns.php?LastBurnId=" + maxBurnId)
            .then(function(response) {
                //console.log(response.data);
                $scope.BurnFeed = $scope.BurnFeed.concat(response.data);
            });
    };

    $scope.postBurn = function(e) {
        $scope.loading = true;
        $http.post("Actions/PostBurn.php", $scope.addBurnData)
            .then(function(response) {
                //console.log(response);
                $scope.loading = false;
                $scope.addBurnData = {};
                instance.$onInit();
            });
    };

    $scope.updateState = function() {
        $scope.loading = true;
        $http.post("Actions/UpdateCurrentState.php", {})
            .then(function(response) {
                //console.log(response);
                $scope.loading = false;
                $scope.refreshState();
            });
    }

    $interval(function() {
        $scope.demotionTimer = $scope.demotionDate.toCountDownString();
        $scope.resetTimer = $scope.resetDate.toCountDownString();
    }, 500);

    $interval(function() {
        $scope.updateState(); //every two minutes update and refresh state
    }, 10000);

});
