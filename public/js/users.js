Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
new Vue({
    el: '#manage-vue',
    data: {
        users: [],
        pagination: {
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1
        },
        offset: 4,
        formErrors: {},
        formErrorsUpdate: {},
        groups: [],
        checkedGroups: [],
        newUser: {'userName': '', 'password': '', 'firstName': '', 'lastName': '', 'dateOfBirth': '', 'groupName': ''},
        fillUser: {
            'userName': '', 'password': '', 'firstName': '', 'lastName': '', 'dateOfBirth': '', 'groupName': '', 'id': '',
        }
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            let pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

    ready: function () {
        this.getData(this.pagination.current_page);

    },
    methods: {
        getData: function (page) {
            this.$http.get('/groupslist').then((response) => {
                this.$set('groups', response.data);
            });

            this.$http.get('/users?page=' + page).then((response) => {
                this.$set('users', response.data.data.data);
                this.$set('pagination', response.data.pagination);
            });
        },
        createUser: function () {
            const input = this.newUser;
            this.newUser.groupName = this.checkedGroups.toString();
            this.$http.post('/users', input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newUser = {
                    'userName': '', 'password': '', 'firstName': '', 'lastName': '', 'dateOfBirth': '',
                    'groupName': ''
                };
                $("#create-user").modal('hide');
                toastr.success('User Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
            this.checkedGroups = [];
        },
        deleteUser: function (user) {
            this.$http.delete('/users/' + user.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('User Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },
        editUser: function (user) {
            this.fillUser.id = user.id;
            this.fillUser.userName = user.userName;
            this.fillUser.password = user.password;
            this.fillUser.firstName = user.firstName;
            this.fillUser.lastName = user.lastName;
            this.fillUser.groupName = user.groupName;
            if(user.groupName)
            this.checkedGroups = user.groupId.split(",")
            this.fillUser.dateOfBirth = user.dateOfBirth;
            $("#edit-user").modal('show');
        },
        updateUser: function (id) {
            this.fillUser.groupName = this.checkedGroups.toString();
            const input = this.fillUser;
            this.$http.put('/users/' + id, input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newUser = {
                    'userName': '', 'password': '', 'firstName': '', 'lastName': '', 'dateOfBirth': '',
                    'groupName': '', 'id': ''
                };
                $("#edit-user").modal('hide');
                toastr.success('User Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
            this.checkedGroups = [];
        },
        changePage: function (page) {
            this.pagination.current_page = page;
            this.getData(page);
        }
    }
});