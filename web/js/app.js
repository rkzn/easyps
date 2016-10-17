(function () {
    angular.module('EasyPS', []).controller('DefaultController', ['$scope', '$http', function ($scope, $http)
    {
        $scope.registerClient = function(form) {
            if (form.$valid) {
                var req = {
                    method: 'POST',
                    url: Routing.generate('api_post_user'),
                    params: {
                        username: form['registration_form[username]']['$modelValue'],
                        country: form['registration_form[country]']['$modelValue'],
                        city: form['registration_form[city]']['$modelValue'],
                        currency: form['registration_form[currency]']['$modelValue']
                    }
                };

                $http(req).success(function (data) {

                    console.log('registerClient', data);

                    if (data.success) {
                    // OK
                    } else {
                    // error
                    }
                 });
            } else {
                console.log('registerClient errors', form.$error);
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
                url: Routing.generate('api_post_deposit_wallet'),
                params: {  }
            };
            console.log('depositWallet', req, form);
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
                url: Routing.generate('api_post_transfer'),
                params: {
                }
            };
            console.log('transferMoney', req, form);

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