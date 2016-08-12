<div class="portlet" ng-if="auth.isAuthenticated()">
	<div class="portlet-title">
		<div class="actions">
			<a ui-sref="customers.edit({id: 'new'})" class="btn btn-default btn-sm">Add</a>
			<a href="#" class="btn btn-default btn-sm" ng-click="tableParams.reload()">Refresh</a>
			<a href="#" class="btn btn-default btn-sm" ng-click="tableParams.filter({})">Clear filter</a>
		</div>
		<h2>List of Customers</h2>
	</div>
	<div class="portlet-body">
		<div class="row padding-top-10">
			<div class="col-xs-6">
				<select ui-select2="{allowClear: true}" data-placeholder="Filter store..." class="form-control" ng-model="tableParams.filter()['store_id']">
					<option></option>
					<option ng-repeat="item in stores" value="{{item.id}}">{{item.name}}</option>
				</select>
			</div>
			<div class="col-xs-6">
				<input type="text" class="form-control" placeholder="search..." ng-model="tableParams.filter()['search']"/>
			</div>
		</div>
		<table class="table" ng-table="tableParams">
			<tbody>
			<tr class="odd gradeX" ng-repeat="data in $data">
				<td>
					<input type="checkbox" ng-model="selectedItems[data.id]" />
				</td>
				<td data-title="'Brief'">
					<a ui-sref="admin.suppliers.edit({id:data.id})">
						<h3 ng-class="">{{data.given_name}} {{data.family_name}}</h3>
					</a>
					<p>
						{{data.address_line_1}}<br/>
						{{data.address_line_2}}<br/>
						City: {{data.city}}<br/>
					</p>
				</td>
				<td data-title="'Contact'">
					<div class="thumbnail" align="right">
						<p>
							<a class="btn default btn-xs green" ui-sref="customers.edit({id:data.id})">
								<i class="fa fa-edit"></i>
								Edit
							</a>
						</p>
						<p>
							<a href="mailto:{{data.email}}">{{data.email}}</a><br/>
							Phone: {{data.phone}}
						</p>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>