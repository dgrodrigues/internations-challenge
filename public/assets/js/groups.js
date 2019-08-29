$(document).ready(function () {
    bindPopupLink();
});

function bindPopupLink() {
    $('.ajax-add-group').magnificPopup({
        type: 'ajax'
    });

    $('.ajax-edit-group').magnificPopup({
        type: 'ajax'
    });

    $('.ajax-edit-users').magnificPopup({
        type: 'ajax',
        closeOnContentClick: false,
        closeOnBgClick: false,
        callbacks: {
            ajaxContentAdded: function () {
                initUsersUpdate();
            }
        }
    });
}

var groupsView = new Vue({
    el: '#groups',
    data: {
        groups: groupsArray,
        confirmModal: false,
        selectedElementText: '',
        selectedElement: null
    },
    delimiters: ["<%", "%>"],
    updated: function () {
        bindPopupLink();
    },
    methods: {
        askDelete(g) {
            this.selectedElement = g;
            this.selectedElementText = g.name;
            this.confirmModal = true;
        },
        cancelDelete() {
            this.selectedElement = null;
            this.selectedElementText = '';
            this.confirmModal = false;
        },
        confirmDelete() {
            var self = this;
            axios.get('/groups/delete/' + this.selectedElement.id)
                .then(function (response) {
                    if (response.data.success) {
                        self.cancelDelete();
                        self.reloadGroups();
                    } else {
                        toastr.error('Error deleting Group. Please try again.');
                    }
                })
                .catch(function (error) {
                    toastr.error(error);
                })
        },
        reloadGroups() {
            var self = this;
            axios.get('/groups/list')
                .then(function (response) {
                    if (response.data.groups) {
                        self.groups = response.data.groups;
                    } else {
                        toastr.error('Error loading group list. Please try again.');
                    }
                })
                .catch(function (error) {
                    toastr.error(error);
                })
        },
        createGroup(data) {
            var self = this;
            axios({
                method: 'post',
                url: '/groups/add',
                config: {
                    headers: { 'Content-Type': 'multipart/form-data' }
                },
                data: data
            }).then(function (response) {
                if (response.data.success) {
                    self.reloadGroups();
                    $.magnificPopup.close();
                } else {
                    toastr.error('Error adding Group. Please try again.');
                }
            }).catch(function (error) {
                toastr.error(error);
            })
        },
        updateGroup(data, group) {
            var self = this;
            axios({
                method: 'post',
                url: '/groups/update/' + group,
                config: {
                    headers: { 'Content-Type': 'multipart/form-data' }
                },
                data: data
            }).then(function (response) {
                if (response.data.success) {
                    self.reloadGroups();
                    $.magnificPopup.close();
                } else {
                    toastr.error('Error updating Group. Please try again.');
                }
            }).catch(function (error) {
                toastr.error(error);
            })
        }
    }
});

function updateGroup(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();
    var groupId = e.target.getAttribute('data-id');

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }
    groupsView.updateGroup(formData, groupId);
}

function createGroup(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }
    groupsView.createGroup(formData);
}

function closePopup(e) {
    $.magnificPopup.close();
    if (userUpdate !== undefined) {
        userUpdate.$destroy();
        userUpdate = undefined;
    }
}

var userUpdate = undefined;
function initUsersUpdate() {
    userUpdate = new Vue({
        el: '#update-users',
        data: {
            group_id: group,
            users_in: users_in_render,
            users_out: users_out_render,
            selectedInElements: [],
            selectedOutElements: []
        },
        delimiters: ["<%", "%>"],
        methods: {
            toogleInElement(elem) {
                this.selectedOutElements = [];
                var id = elem.target.getAttribute('data-id');
                if (this.selectedInElements.includes(id)) {
                    var index = this.selectedInElements.indexOf(id);
                    if (index !== -1)
                        this.selectedInElements.splice(index, 1);
                } else {
                    this.selectedInElements.push(id);
                }
            },
            toogleOutElement(elem) {
                this.selectedInElements = [];
                var id = elem.target.getAttribute('data-id');
                if (this.selectedOutElements.includes(id)) {
                    var index = this.selectedOutElements.indexOf(id);
                    if (index !== -1)
                        this.selectedOutElements.splice(index, 1);
                } else {
                    this.selectedOutElements.push(id);
                }
            },
            transferOut() {
                var self = this;
                for (var i = 0; i < this.selectedInElements.length; i++) {
                    this.selectedOutElements.push(this.selectedInElements[i]);
                    var index = this.users_in.findIndex(function (user) {
                        return user.id == self.selectedInElements[i];
                    });
                    if (index !== -1) {
                        this.users_out.push(this.users_in[index]);
                        this.users_in.splice(index, 1);
                    }
                }
                this.selectedInElements = [];
            },
            transferIn() {
                var self = this;
                for (var i = 0; i < this.selectedOutElements.length; i++) {
                    this.selectedInElements.push(this.selectedOutElements[i]);
                    var index = this.users_out.findIndex(function (user) {
                        return user.id == self.selectedOutElements[i];
                    });
                    if (index !== -1) {
                        this.users_in.push(this.users_out[index]);
                        this.users_out.splice(index, 1);
                    }
                }
                this.selectedOutElements = [];
            },
            submitUsersUpdate() {
                var self = this;
                var user_ids = this.users_in.map(function (user) {
                    return user.id;
                }).toString();

                var formData = new FormData();
                formData.set('users', user_ids);

                axios({
                    method: 'post',
                    url: '/groups/users/update/' + this.group_id,
                    data: formData
                }).then(function (response) {
                    if (response.data.success) {
                        groupsView.reloadGroups();
                        closePopup();
                    } else {
                        toastr.error('Error updating Group users. Please try again.');
                    }
                }).catch(function (error) {
                    toastr.error(error);
                })

            }
        }
    })
}