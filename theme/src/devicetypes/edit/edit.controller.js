'use strict';

angular.module('biffyApp').controller(
    'DeviceTypesEditController',
    function($rootScope, $scope, $stateParams, $state, RestangularAppService, NotifierService, $modal, ngTableParams, $location)
    {
        $scope.languageList = RestangularAppService.all('languages').getList({all: 1}).$object;
        $scope.deviceManufacturerList = RestangularAppService.all('devicemanufacturers').getList({all: 1}).$object;
        $scope.deviceFamilyList = RestangularAppService.all('devicefamilies').getList({all : 1}).$object;

        RestangularAppService.all('devicetypes').getList({all:'1'}).then(
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

        $scope.id = $stateParams.deviceTypeId;
        $scope.data = {};
        $scope.defaultData = { parent_device_type_id: 0, device_manufacturer: 12, device_family: 24, sort_order: 0 };
        $scope.mode = 'Loading';

        $scope.createKeyNames = function()
        {
            var languageKeyBase = 'device_type_' + $scope.id + '_';

            $scope.nameLanguageKey = languageKeyBase + 'name';
            $scope.metaDescriptionLanguageKey = languageKeyBase + 'meta_description';
            $scope.metaKeywordsLanguageKey = languageKeyBase +  'meta_keywords';
            $scope.webDescriptionLanguageKey = languageKeyBase + 'web_description';

            $scope.languageKeySet = [
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
            if ($scope.isAdd())
            {
                $scope.data = angular.copy($scope.defaultData);

                $scope.nameAttributes = [];
                $scope.metaDescriptionAttributes = [];
                $scope.metaKeywordsAttributes = [];
                $scope.webDescriptionAttributes = [];
            }
            else
            {
                RestangularAppService.one('devicetypes', $scope.id).get().then(
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

                $scope.nameAttributes = RestangularAppService.one('languagekeys', $scope.nameLanguageKey).all('strings').getList().$object;
                $scope.metaDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.metaDescriptionLanguageKey).all('strings').getList().$object;
                $scope.metaKeywordsAttributes = RestangularAppService.one('languagekeys', $scope.metaKeywordsLanguageKey).all('strings').getList().$object;
                $scope.webDescriptionAttributes = RestangularAppService.one('languagekeys', $scope.webDescriptionLanguageKey).all('strings').getList().$object;
            }
        };

        $scope.init();

        $scope.createNewDeviceRepair = function()
        {
            var modalInstance = $modal.open(
                {
                    templateUrl : 'src/modals/devicetypes/create.repair.template.html',
                    controller : 'EditDeviceTypeRepairModalController',
                    size : 'lg',
                    resolve : {
                        deviceTypeId  : function()
                        {
                            return $scope.id;
                        },
                        deviceTypeRepairId : function()
                        {
                            return 'new';
                        }
                    }
                }
            );

            modalInstance.result.then(
                function()
                {
                    $scope.deviceRepairTypeTableParams.reload();
                },
                function()
                {
                    $scope.deviceRepairTypeTableParams.reload();
                }
            );
        };

        $scope.deleteDeviceRepair = function(id)
        {
            RestangularAppService.one('devicerepairs', id).remove().then(
                function()
                {
                    $scope.deviceRepairTypeTableParams.reload();
                },
                function()
                {

                }
            );
        };

        $scope.store = function()
        {
            RestangularAppService.all('devicetypes').post($scope.data).then(
                function(data)
                {
                    $scope.id = data.id;

                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet(
                        $scope.languageKeySet,
                        function()
                        {
                            $state.transitionTo('devicetypes');
                        }
                    );
                },
                function(data)
                {
                    NotifierService.error('Could not add ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.storeAndNew = function()
        {
            RestangularAppService.all('devicetypes').post($scope.data).then(
                function(data)
                {
                    $scope.id = data.id;

                    $scope.createKeyNames();

                    $scope.saveLanguageKeySet(
                        $scope.languageKeySet,
                        function()
                        {
                            $scope.init();
                        }
                    );
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

                    $scope.saveLanguageKeySet(
                        $scope.languageKeySet,
                        function()
                        {
                            $state.transitionTo('devicetypes')
                        }
                    );
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
                    $state.transitionTo('devicetypes');
                },
                function(data)
                {
                    NotifierService.error('Could not delete ' + JSON.stringify(data.data.messages));
                }
            );
        };

        $scope.cancel = function()
        {
            $state.transitionTo('devicetypes');
        };

        $scope.deviceRepairTypeTableParams = new ngTableParams(
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
                    RestangularAppService.all('devicerepairs?filter[device_type_id]=' + $scope.id).getList().then(
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

        $scope.saveLanguageKeySet = function(languageKeySet, callback)
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
                                        callback();
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
