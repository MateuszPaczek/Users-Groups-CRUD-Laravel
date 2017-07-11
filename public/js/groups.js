Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
new Vue({
    el: '#manage-vue',
    data: {
        groups: [],
        usersName: [],
        users: [],
        checkedUsers: [],
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
        newGroup: {'groupName': '', 'usersNames': ''},
        fillGroup: {'groupName': '', 'usersNames': '', 'id': ''}
    },

    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

    ready: function () {
        this.getVueGroups(this.pagination.current_page);

    },
    methods: {
        getVueGroups: function (page) {

            this.$http.get('/users').then((response) => {
                this.$set('usersName', response.data.data.data);
                for( i=0;i<this.usersName.length;i++){
                    this.users.push(this.usersName[i].userName);
                }
            });


            this.$http.get('/groups?page=' + page).then((response) => {
                this.$set('groups', response.data.data.data);
                this.$set('pagination', response.data.pagination);
            });

        },
        createGroup: function () {
            var input = this.newGroup;
            this.newGroup.usersNames = this.checkedUsers.toString();
            this.$http.post('/groups', input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newGroup = {'groupName': '', 'usersNames': ''};
                $("#create-group").modal('hide');
                toastr.success('Group Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
            this.checkedUsers = [];
        },
        deleteGroup: function (group) {
            this.$http.delete('/groups/' + group.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Group Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },
        editGroup: function (group) {
            this.fillGroup.id = group.id;
            this.fillGroup.groupName = group.groupName;
            this.fillGroup.usersNames = group.usersNames;
            if(group.usersNames)
            this.checkedUsers = group.usersNames.split(',');
            $("#edit-group").modal('show');
        },
        updateGroup: function (id) {
            this.fillGroup.usersNames = this.checkedUsers.toString();
            var input = this.fillGroup;
            this.$http.put('/groups/' + id, input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newGroup = {'groupName': '', 'usersNames': '', 'id': ''};
                $("#edit-group").modal('hide');
                toastr.success('Group Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
            this.checkedUsers = [];
        },
        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueGroups(page);
        }
    }
});