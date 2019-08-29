$(document).ready(function () {
    bindPopupLink();
});

function bindPopupLink() {
    $('.ajax-add-user').magnificPopup({
        type: 'ajax'
    });

    $('.ajax-edit-user').magnificPopup({
        type: 'ajax'
    });
}

var usersView = new Vue({
    el: '#users',
    data: {
        users: usersArray,
        confirmModal: false,
        selectedElementText: '',
        selectedElement: null
    },
    updated: function () {
        bindPopupLink();
    },
    delimiters: ["<%", "%>"],
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
            axios.get('/users/delete/' + this.selectedElement.id)
                .then(function (response) {
                    if (response.data.success) {
                        self.cancelDelete();
                        self.reloadUsers();
                    } else {
                        toastr.error('Error deleting User. Please try again.');
                    }
                })
                .catch(function (error) {
                    toastr.error(error);
                })
        },
        reloadUsers() {
            var self = this;
            axios.get('/users/list')
                .then(function (response) {
                    if (response.data.users) {
                        self.users = response.data.users;
                    } else {
                        toastr.error('Error loading users list. Please try again.'); 
                    }
                })
                .catch(function (error) {
                    toastr.error(error);
                })
        },
        createUser(data) {
            var self = this;
            axios({
                method: 'post',
                url: '/users/add',
                config: {
                    headers: { 'Content-Type': 'multipart/form-data' }
                },
                data: data
            }).then(function (response) {
                if (response.data.success) {
                    self.reloadUsers();
                    $.magnificPopup.close();
                } else {
                    toastr.error('Error adding User. Please try again.');
                }
            }).catch(function (error) {
                toastr.error(error);
            })
        },
        updateUser(data, user) {
            var self = this;
            axios({
                method: 'post',
                url: '/users/update/' + user,
                config: {
                    headers: { 'Content-Type': 'multipart/form-data' }
                },
                data: data
            }).then(function (response) {
                if (response.data.success) {
                    self.reloadUsers();
                    $.magnificPopup.close();
                } else {
                    toastr.error('Error updating User. Please try again.');
                }
            }).catch(function (error) {
                toastr.error(error);
            })
        }
    }
});

function updateUser(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();
    var userId = e.target.getAttribute('data-id');

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }
    usersView.updateUser(formData, userId);
}

function createUser(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }
    usersView.createUser(formData);
}

function closePopup(e) {
    $.magnificPopup.close();
}