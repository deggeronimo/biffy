<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="/">Dashboard</a></li>
        <li class="active">User Management</li>
    </ol>

    <h1>User Management</h1>
</div>

<div class="container" ng-controller="UserController as UserCtrl">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>Users</h4>
                </div>
                <div class="panel-body">
                    <div style="height: 500px;" ng-grid="gridOptions"></div>
                </div>
            </div>
        </div>
    </div>
</div>