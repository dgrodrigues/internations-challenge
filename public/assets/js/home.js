$(document).ready(function () {
    bindPopupLink();
});

function bindPopupLink() {
    $('.ajax-add-user').magnificPopup({
        type: 'ajax'
    });

    $('.ajax-add-group').magnificPopup({
        type: 'ajax'
    });
}

function createUser(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }

    axios({
        method: 'post',
        url: '/users/add',
        config: {
            headers: { 'Content-Type': 'multipart/form-data' }
        },
        data: formData
    }).then(function (response) {
        if (response.data.success) {
            $.magnificPopup.close();
            window.location.href = '/users';
        } else {
            toastr.error('Error adding User. Please try again.');
        }
    }).catch(function (error) {
        toastr.error(error);
    });
}

function createGroup(e) {
    e.preventDefault();
    var values = $(e.target).serializeArray();
    var formData = new FormData();

    for (var i = 0; i < values.length; i++) {
        formData.set(values[i].name, values[i].value);
    }

    axios({
        method: 'post',
        url: '/groups/add',
        config: {
            headers: { 'Content-Type': 'multipart/form-data' }
        },
        data: formData
    }).then(function (response) {
        if (response.data.success) {
            window.location.href = '/groups';
            $.magnificPopup.close();
        } else {
            toastr.error('Error adding Group. Please try again.');
        }
    }).catch(function (error) {
        toastr.error(error);
    });
}


function closePopup(e) {
    $.magnificPopup.close();
}


