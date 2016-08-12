'use strict';

angular.module('biffyApp').controller(
    'DeviceTypesEditRepairsController',
    function($rootScope, $scope, $stateParams, $state, RestangularAppService, NotifierService, ngTableParams, $location, $modal)
    {
        $scope.languageList = RestangularAppService.all('languages').getList().$object;
        $scope.trueFalse = [ { id: 0, value: 'False'}, { id: 1, value: 'True' } ];

        RestangularAppService.all('devicetypes').getList().then(
            function(result)
            {
                $scope.deviceTypes = result.plain();
                $scope.deviceTypeOptions = [ { id: 0, name: 'Root' } ].concat($scope.deviceTypes);
            },
            function()
            {

            }
        );

        $scope.trueFalse = [ { id: 0, value: 'False'}, { id: 1, value: 'True' } ];

        $scope.id = $stateParams.deviceRepairId;
        $scope.data = { parent_device_type_id : 0, selectable : 0, top : 0 };
        $scope.mode = 'Loading';

        $scope.createKeyNames = function()
        {
            var languageKeyBase = 'device_repair_' + $scope.id + '_';

            $scope.estimatedCostLanguageKey = languageKeyBase + 'estimated_cost';
            $scope.nameLanguageKey = languageKeyBase + 'name';
            $scope.metaDescriptionLanguageKey = languageKeyBase + 'meta_description';
            $scope.metaKeywordsLanguageKey = languageKeyBase +  'meta_keywords';
            $scope.webDescriptionLanguageKey = languageKeyBase + 'web_description';

            $scope.languageKeySet = [
                { key: $scope.estimatedCostLanguageKey, values: $scope.estimatedCostAttributes },
                { key: $scope.nameLanguageKey, values: $scope.nameAttributes },
                { key: $scope.metaDescriptionLanguageKey, values: $scope.metaDescriptionAttributes },
                { key: $scope.metaKeywordsLanguageKey, values: $scope.metaKeywordsAttributes },
                { key: $scope.webDescriptionLanguageKey, values: $scope.webDescriptionAttributes }
            ];
        };

        $scope.isAdd = function()
        {
            return $scope.id === 'new';
        };

        $scope.isEdit = function()
        {
            return $scope.mode === 'Edit';
        };

        $scope.init = function()
        {
            $scope.languageList = RestangularAppService.all('languages').getList().$object;

            if ($scope.isAdd())
            {
                $scope.estimatedCostAttributes = [];
                $scope.nameAttributes = [];
                $scope.metaDescriptionAttributes = [];
                $scope.metaKeywordsAttributes = [];
                $scope.webDescriptionAttributes = [];
            }
            else
            {
                RestangularAppService.one('devicerepairs', $scope.id).get().then(
                    function(data)
                    {
                        $scope.mode = 'Edit';

                        $scope.data = data;
                    },
                    function(data)
                    {
                        NotifierService.error('Invalid reference, cannot load data ' + JSON.stringify(data.data.messages));
                    }
                );

                $scope.createKeyNames();

                $scope.estimatedCostAttributes = RestangularAppService.one('languagekeys', $scope.estimatedCostLanguageKey).all('strings').getList().$object;
                $scope.nameAttributes = RestangularAppService.one('languagekeys', $scope.nameLanguageKey).all('strings').getList().$object;
                $scope.metaDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.metaDescriptionLanguageKey).all('strings').getList().$object;
                $scope.metaKeywordsAttributes = RestangularAppService.one('languagekeys', $scope.metaKeywordsLanguageKey).all('strings').getList().$object;
                $scope.webDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.webDescriptionLanguageKey).all('strings').getList().$object;
            }
        };

        $scope.init();

        $scope.createNewDeviceRepairItem = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/devicetypes/edit/repairs/additem.modal.html',
                    controller : 'EditDeviceTypeRepairItemModalController',
                    size : 'lg',
                    resolve : {
                        deviceRepairId  : function()
                        {
                            return $scope.id;
                        }
                    }
                }
            );

            modalInstance.result.then(
                function()
                {
                    $scope.deviceRepairItemTableParams.reload();
                },
                function()
                {
                    $scope.deviceRepairItemTableParams.reload();
                }
            );
        };

        $scope.deleteDeviceRepairOption = function(id)
        {
            RestangularAppService.one('devicerepairs', $scope.id).one('items', id).remove().then(
                function()
                {
                    $scope.deviceRepairItemTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.store = function()
        {
            RestangularAppService.all('devicerepairs').post($scope.data).then(
                function(data)
                {
                    $scope.id = data.id;

                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'devicetypes.edit', {deviceTypeId:$stateParams.deviceTypeId});
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.update = function()
        {
            $scope.data.put().then(
                function()
                {
                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet($scope.languageKeySet, 'devicetypes.edit', {deviceTypeId:$stateParams.deviceTypeId});
                },
                function(data)
                {
                    NotifierService.error('Could not edit ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.destroy = function()
        {
            $scope.data.remove().then(
                function()
                {
                    $state.transitionTo('devicetypes.edit', {deviceTypeId:$stateParams.deviceTypeId});
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('devicetypes.edit', {deviceTypeId:$stateParams.deviceTypeId});
        };

        $scope.deviceRepairItemTableParams = new ngTableParams(
            angular.extend(
                {
                    page : 1,
                    count : 10,
                    sorting:
                    {
                        name : 'asc'
                    }
                },
                $location.search()
            ),
            {
                total: 0,
                getData: function($defer)
                {
                    RestangularAppService.one('devicerepairs', $scope.id).all('items').getList().then(
                        function(result)
                        {
                            $defer.resolve(result);
                        },
                        function()
                        {

                        }
                    );
                }
            }
        );

        $scope.saveLanguageKeySet = function(languageKeySet, transition)
        {
            var finished = 0;

            languageKeySet.forEach(
                function(element, index, array)
                {
                    RestangularAppService.one('languagekeys', element.key).all('strings').getList().then(
                        function(data)
                        {
                            for (var i=0; i<$scope.languageList.length; i++)
                            {
                                if (element.values[i])
                                {
                                    element.values[i].id = data[i].id;
                                }
                                else
                                {
                                    element.values[i] = { id: data[i].id }
                                }
                            }

                            RestangularAppService.one('languagekeys', element.key).one('strings', $scope.id).customPUT({strings:element.values}).then(
                                function()
                                {
                                    finished ++;

                                    if (finished == languageKeySet.length)
                                    {
                                        $state.transitionTo(transition, {deviceTypeId:$stateParams.deviceTypeId});
                                    }
                                },
                                function()
                                {
                                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                                }
                            );
                        },
                        function()
                        {
                            NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                        }
                    );
                }
            );
        };
    }
);
