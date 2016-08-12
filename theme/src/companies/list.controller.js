'use strict';

/* jshint newcap: false */

angular.module('biffyApp').controller(
    'CompaniesListController',
    function ($rootScope, $state, $stateParams, $scope, $q, $location, $filter, $modal, ngTableParams, RestangularAppService, NotifierService, ExportService)
    {
        $scope.tableParams = new ngTableParams(
            angular.extend(
                {
                    page: 1,
                    count: 10,
                    sorting: {
                        name: 'asc'
                    }
                },
                $location.search()
            ), {
                total: 0,
                getData: function($defer, params)
                {
                    $location.search(params.url());
                    RestangularAppService.all('companies').getList(params.url()).then(
                        function(result)
                        {
                            $scope.tableParams.settings({total: result.paginator.total});
                            $defer.resolve(result);
                        },
                        function()
                        {
                            NotifierService.error('Companies could not be loaded');
                        }
                    );
                }
            }
        );

        $scope.currentContactType = "home";

        $scope.isAdd = function()
        {
            return $scope.id === null;
        };

        $scope.companiesEdit = function(company)
        {
            $scope.currentContactType = 'edit';
            $scope.id = company.id || null;
            $scope.mode = 'Edit';
            $scope.company = angular.copy(company.plain());
//            $scope.tableParams.reload();
        };

        $scope.companiesAdd = function()
        {
            $scope.currentContactType = 'add';
            $scope.mode = 'Add';
            $scope.company = {};
        };

        $scope.store = function()
        {
            RestangularAppService.all('companies').post($scope.company).then(
                function(result)
                {
                    $scope.currentContactType = 'cancel';
                    $scope.tableParams.reload();
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            RestangularAppService.one('companies', $scope.company.id).put($scope.company).then(
                function()
                {
                    $scope.currentContactType = 'cancel';
                    $scope.tableParams.reload();
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.company.remove().then(
                function()
                {
                    $scope.currentContactType = 'cancel';
                    $scope.tableParams.reload();
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $scope.currentContactType = 'cancel';
        };

        $scope.export = function()
        {
            ExportService.go('companies', $location.search());
        };

        $scope.getContact = function(e)
        {
            $scope.currentCompanyId = e.id;
            $scope.currentContactType = 'getting contact';

            RestangularAppService.one('companies', e.id).all('contacts').getList().then(
                function(result)
                {
                    $scope.contactData = result;
                },
                function()
                {
                    NotifierService.error('Contacts could not be loaded');
                }
            );
        };

        $scope.contactInfo = function(e)
        {
            var modalInstance = $modal.open(
                {
                    templateUrl: 'src/modals/companies.edit.contact.modal.html',
                    controller: 'CompaniesEditContactModalController',
                    size: 'lg',
                    resolve: {
                        companyId: function()
                        {
                            return e.company_id;
                        },
                        contactId: function()
                        {
                            return e.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function(result)
                {
                    for (var i = 0; i < $scope.contactData.length; i++)
                    {
                        if ($scope.contactData[i].id == result.id)
                        {
                            $scope.contactData[i] = result;
                            break;
                        }
                    }
                },
                function()
                {
                }
            );
        };

        $scope.cancelContact = function()
        {
            $scope.currentContactType = 'cancel';
        };

        $scope.updateContact = function()
        {
            $scope.currentContactType = 'contact edit';
        };

        $scope.export = function()
        {
            ExportService.go('companies', $location.search());
        };

        $scope.addNewContact = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl: 'src/modals/companies.new.contact.modal.html',
                    controller: 'CompaniesNewContactModalController',
                    size: 'lg',
                    resolve: {
                        companyId: function()
                        {
                            return $scope.currentCompanyId;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function (result)
                {
                    $scope.contactData.push(result);
                },
                function()
                {
                }
            );
        }
    }
);
