(function () {
    angular.module('EasyPS', []).controller('DefaultController', ['$scope', '$http', function ($scope, $http) {
        $scope.registerClient = function(form) {
            console.log('registerClient', form);

            if (form.username && form.country) {
                $scope.login();
            }
        };

        $scope.login = function() {
            $scope.inWallet = true;
            $scope.amount = 0;
            $scope.currency = 'USD';
            $scope.errors = 0;
            //$scope.loadWallet();
        };

        $scope.logout = function () {
            $scope.inWallet = false;
        };

        $scope.addRate = function (form) {
            console.log('addRate', form);
        };

        $scope.depositWallet = function (form) {
            var req = {
                method: 'POST',
                //url: Routing.generate('api_deposit_wallet'),
                params: { 'amount': $scope.amount }
            };
            console.log('depositWallet', form);
            /*$http(req).success(function (data) {
                if (data.success) {
                    // OK
                } else {
                    // error
                }
            });*/
        };

        $scope.transferMoney = function (form) {
            var req = {
                method: 'POST',
                //url: Routing.generate('api_transfer_money'),
                params: {
                    'receiver': form.receiver,
                    'amount': form.amount,
                    'currency': form.currency
                }
            };
            console.log('transferMoney', form);

            /*$http(req).success(function (data) {
                if (data.success) {
                    // OK
                } else {
                    // error
                }
            });*/
        };


    }]);
})();