@extends('layout')

@section('content')

    <div class="row">
        <div class="pull-left">
            <h2>Users</h2>
        </div>
        <div class="pull-right">
            <button type="button" data-toggle="modal" data-target="#create-user" class="btn btn-success">
                Create user
            </button>
        </div>

        <table id="usersTable" class="table table-responsive table-hover table-bordered">
            <tr>
                <th>ID</th>
                <th>User name</th>
                <th>Password</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Date of birth</th>
                <th>Groups</th>
                <th width="180px">Action</th>
            </tr>
            <tr v-for="user in users">
                <td>@{{ user.id }}</td>
                <td>@{{ user.userName }}</td>
                <td>@{{ user.password }}</td>
                <td>@{{ user.firstName }}</td>
                <td>@{{ user.lastName }}</td>
                <td>@{{ user.dateOfBirth }}</td>
                <td>@{{ user.groupName }}</td>
                <td>
                    <button class="edit-modal btn btn-warning" @click.prevent="editUser(user)">
                        <span class="glyphicon glyphicon-edit"></span> Edit
                    </button>
                    <button class="edit-modal btn btn-danger" @click.prevent="deleteUser(user)">
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
        <div class="modal fade" id="create-user" tabindex="-1" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">x</span>
                        </button>
                        <h4 class="modal-title" id="CreateModalLabel">Create user</h4>
                    </div>

                    <div class="modal-body">

                        <form method="post" enctype="multipart/form-data" v-on:submit.prevent="createUser">
                            <div class="form-group">
                                <label for="userName">User name:</label>
                                <input  type="text" name="userName" class="form-control" v-model="newUser.userName" />
                                <span v-if="formErrors['userName']" class="error text-danger">
                                    @{{ formErrors['userName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="password">password:</label>
                                <input  type="text" name="password" class="form-control" v-model="newUser.password" />
                                <span v-if="formErrors['password']" class="error text-danger">
                                    @{{ formErrors['password'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="firstName">First name:</label>
                                <input  type="text" name="firstName" class="form-control" v-model="newUser.firstName" />
                                <span v-if="formErrors['firstName']" class="error text-danger">
                                    @{{ formErrors['firstName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="lastName">Last name:</label>
                                <input  type="text" name="lastName" class="form-control" v-model="newUser.lastName" />
                                <span v-if="formErrors['lastName']" class="error text-danger">
                                    @{{ formErrors['lastName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="dateOfBirth">Date of birth:</label>
                                <input  type="date" name="dateOfBirth" class="form-control" v-model="newUser.dateOfBirth">
                                <span v-if="formErrors['dateOfBirth']" class="error text-danger">
                                    @{{ formErrors['dateOfBirth'] }}</span>
                            </div>

                            <label for="groupName">Groups:</label>
                                <span v-for="group in groups">
                                    <input type="checkbox" id="@{{ group.id }}" value="@{{ group.id }}"
                                           v-model="checkedGroups"/>
                                        <label for="@{{ group.id }}">@{{ group.groupName }}</label>
                                </span>


                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


         <!-- Edit -->
        <div class="modal fade" id="edit-user" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="EditModalLabel">Edit user</h4>
                    </div>

                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" v-on:submit.prevent="updateUser(fillUser.id)">

                            <div class="form-group">
                                <label for="userName">User name:</label>
                                <input  type="text" name="userName" class="form-control" v-model="fillUser.userName" />
                                <span v-if="formErrorsUpdate['userName']" class="error text-danger">
                                    @{{ formErrorsUpdate['userName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="password">password:</label>
                                <input  type="text" name="password" class="form-control" v-model="fillUser.password" />
                                <span v-if="formErrorsUpdate['password']" class="error text-danger">
                                        @{{ formErrorsUpdate['password'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="firstName">First name:</label>
                                <input  type="text" name="firstName" class="form-control" v-model="fillUser.firstName" />
                                <span v-if="formErrorsUpdate['firstName']" class="error text-danger">
                                    @{{ formErrorsUpdate['firstName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="lastName">Last name:</label>
                                <input  type="text" name="lastName" class="form-control" v-model="fillUser.lastName" />
                                <span v-if="formErrorsUpdate['lastName']" class="error text-danger">
                                    @{{ formErrorsUpdate['lastName'] }}</span>
                            </div>

                            <div class="form-group">
                                <label for="dateOfBirth">Date of birth:</label>
                                <input  type="date" name="dateOfBirth" class="form-control"
                                        v-model="fillUser.dateOfBirth"/>
                                <span v-if="formErrorsUpdate['dateOfBirth']" class="error text-danger">
                                    @{{ formErrorsUpdate['dateOfBirth'] }}</span>
                            </div>

                            <label for="groupName">Groups:</label>
                            <span v-for="group in groups">
                                    <input type="checkbox" id="@{{ group.id }}" value="@{{ group.id }}"
                                           v-model="checkedGroups"/>
                                        <label for="@{{ group.id }}">@{{ group.groupName }}</label>
                                </span>


                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

         <a href="/managegroups">Manage groups</a>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/js/users.js"></script>
@endsection