@extends('layout')

@section('content')

	<div class="row">
		<div class="pull-left">
			<h2>Groups</h2>
		</div>
		<div class="pull-right">
			<button type="button" data-toggle="modal" data-target="#create-group" class="btn btn-success">
				Create group
			</button>
		</div>

		<table id="groupsTable" class="table table-responsive table-hover table-bordered">
			<tr>
				<th>ID</th>
				<th>Group name</th>
				<th>Users</th>
				<th width="180px">Action</th>
			</tr>
			<tr v-for="group in groups">
				<td>@{{ group.id }}</td>
				<td>@{{ group.groupName }}</td>
				<td>@{{ group.usersNames }}</td>
				<td>
					<button class="edit-modal btn btn-warning" @click.prevent="editGroup(group)">
						<span class="glyphicon glyphicon-edit"></span> Edit
					</button>
					<button class="edit-modal btn btn-danger" @click.prevent="deleteGroup(group)">
						<span class="glyphicon glyphicon-trash"></span> Delete
					</button>
				</td>
			</tr>
		</table>

		<nav>
			<ul class="pagination">
				<li v-if="pagination.current_page > 1">
					<a href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">
						<span aria-hidden="true">«</span>
					</a>
				</li>
				<li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
					<a href="#" @click.prevent="changePage(page)">
						@{{ page }}
					</a>
				</li>
				<li v-if="pagination.current_page < pagination.last_page">
					<a href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">
						<span aria-hidden="true">»</span>
					</a>
				</li>
			</ul>
		</nav>

		<!-- Create  -->
		<div class="modal fade" id="create-group" tabindex="-1" >
			<div class="modal-dialog" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">x</span>
						</button>
						<h4 class="modal-title" id="CreateModalLabel">Create group</h4>
					</div>

					<div class="modal-body">

						<form method="post" enctype="multipart/form-data" v-on:submit.prevent="createGroup">
							<div class="form-group">
								<label for="groupName">Group name:</label>
								<input  type="text" name="groupName" class="form-control" v-model="newGroup.groupName" />
								<span v-if="formErrors['groupName']" class="error text-danger">
									@{{ formErrors['groupName'] }}</span>
							</div>

							<label for="usersNames">Users:</label>
								<span v-for="user in users">
									<input type="checkbox" id="@{{ user }}" value="@{{ user }}" v-model="checkedUsers"/>
										<label for="@{{ user }}">@{{ user }}</label>
								</span>
							<br>
							<span style="word-wrap: break-word">Checked users: @{{ checkedUsers }}</span>

							<div class="form-group">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>


		<!-- Edit -->
		<div class="modal fade" id="edit-group" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="EditModalLabel">Edit group</h4>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data" v-on:submit.prevent="updateGroup(fillGroup.id)">

							<div class="form-group">
								<label for="groupName">Group name:</label>
								<input  type="text" name="groupName" class="form-control" v-model="fillGroup.groupName" />
								<span v-if="formErrorsUpdate['groupName']" class="error text-danger">
									@{{ formErrorsUpdate['groupName'] }}</span>
							</div>

							<label for="usersNames">Users:</label>
							<span v-for="user in users">
									<input type="checkbox" id="@{{ user }}" value="@{{ user }}" v-model="checkedUsers"/>
										<label for="@{{ user }}">@{{ user }}</label>
								</span>
							<br>
							<span style="word-wrap: break-word">Checked users: @{{ checkedUsers }}</span>


							<div class="form-group">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>

		<a href="/manageusers">Manage users</a>
	</div>
@endsection

@section('script')
			<script type="text/javascript" src="/js/groups.js"></script>
@endsection