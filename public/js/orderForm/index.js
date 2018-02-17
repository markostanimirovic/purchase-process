$(document).ready(function () {
    var table = $('#tableData').DataTable({
        processing: true,
        columnDefs: [
            {orderable: false, targets: -1},
            {width: "15%", targets: -1}
        ],
        language: {
            "sProcessing": "Procesiranje u toku...",
            "sLengthMenu": "Prikaži _MENU_ elemenata",
            "sZeroRecords": "Nije pronađen nijedan rezultat",
            "sInfo": "Prikaz _START_ do _END_ od ukupno _TOTAL_ elemenata",
            "sInfoEmpty": "Prikaz 0 do 0 od ukupno 0 elemenata",
            "sInfoFiltered": "(filtrirano od ukupno _MAX_ elemenata)",
            "sInfoPostFix": "",
            "sSearch": "Pretraga:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Početna",
                "sPrevious": "Prethodna",
                "sNext": "Sledeća",
                "sLast": "Poslednja"
            },
            "select": {
                "rows": ""
            }
        }
    });

    var modal = $('#confirm-modal');
    var viewModal = $('#view-modal');

    $('#tableData').on('click', '.delete', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda brisanja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da obrišete selektovanu narudžbenicu?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'delete');
        modal.modal('show');
    });

    $('#tableData').on('click', '.send', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda slanja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da pošaljete selektovanu narudžbenicu?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'send');
        modal.modal('show');
    });

    $('#tableData').on('click', '.edit', function () {
        var id = $(this).attr('data-id');
        window.location = '/orderForm/edit/' + id;
    });


    $('#tableData').on('click', '.reverse', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda storniranja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da stornirate selektovanu narudžbenicu?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'reverse');
        modal.modal('show');
    });

    $('#tableData').on('click', '.add-on-existing', function () {
        var id = $(this).attr('data-id');
        window.location = '/orderForm/insertOnExisting/' + id;
    });


    $('#tableData').on('click', '.view', function () {
        var id = $(this).attr('data-id');
        $.get('/orderForm/view/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == "success") {
                fillOrderFormModal(response.orderForm);
                viewModal.modal('show');
            } else {
                echoErrorMessage(response.message);
            }
        });
    });

    $('.confirmed').on('click', function () {
        var id = $(this).attr('data-id');
        var action = $(this).attr('action');

        modal.modal('hide');

        $.get('/orderForm/' + action + '/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == "success") {
                window.location = '/orderForm/';
            } else {
                echoErrorMessages(response.messages);
            }
        });
    });

    $('.insert').on('click', function () {
        window.location = '/orderForm/insert/';
    });

    function fillOrderFormModal(orderForm) {
        $('#code-cell').text(orderForm.code);
        $('#date-cell').text(orderForm.date);
        $('#total-amount-cell').text(orderForm.totalAmount);
        $('#supplier-pib-cell').text(orderForm.supplier.pib);
        $('#supplier-name-cell').text(orderForm.supplier.name);
        $('#street-and-number-cell').text('Ulica: ' + orderForm.supplier.street + ", Broj: " + orderForm.supplier.streetNumber);
        $('#place-cell').text(orderForm.supplier.placeZipCode + ' ' + orderForm.supplier.placeName);
        $('#items-tbody').text('');
        for (var i = 0; i < orderForm.items.length; i++) {
            $('#items-tbody').append('<tr><td>' + orderForm.items[i].code + '</td><td>'
                + orderForm.items[i].name + '</td><td>' + orderForm.items[i].unit +
                '</td><td>' + orderForm.items[i].price.toFixed(2) + '</td><td>' + orderForm.items[i].quantity + '</td>' +
                '<td>' + orderForm.items[i].amount + '</td></tr>');
        }
    }

    function echoErrorMessages(messages) {
        $('.error-messages').text('');
        $.each(messages, function (i, value) {
            $('.error-messages')
                .append('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + value + '</div>');
        });
    }

    function echoErrorMessage(message) {
        $('.error-messages').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span></button>' + message + '</div>');
    }
});

