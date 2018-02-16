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
        modal.find('.modal-body').text('Da li ste sigurni da želite da obrišete selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'delete');
        modal.modal('show');
    });

    $('#tableData').on('click', '.send', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda slanja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da pošaljete selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'send');
        modal.modal('show');
    });

    $('#tableData').on('click', '.edit', function () {
        var id = $(this).attr('data-id');
        window.location = '/catalog/edit/' + id;
    });


    $('#tableData').on('click', '.reverse', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda storniranja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da stornirate selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'reverse');
        modal.modal('show');
    });

    $('#tableData').on('click', '.add-on-existing' , function () {
        var id = $(this).attr('data-id');
        window.location = '/catalog/insertOnExisting/' + id;
    });


    $('#tableData').on('click', '.view', function () {
        var id = $(this).attr('data-id');
        $.get('/catalog/view/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == "success") {
                fillCatalogModal(response.catalog);
                viewModal.modal('show');
            } else {
                $('.error-messages').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + response.message + '</div>');
            }
        });
    });

    $('.confirmed').on('click', function () {
        var id = $(this).attr('data-id');
        var action = $(this).attr('action');

        modal.modal('hide');

        $.get('/catalog/' + action + '/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == "success") {
                window.location = '/catalog/';
            } else {
                echoErrorMessages(response.messages);
            }
        });
    });

    $('.insert').on('click', function () {
       window.location = '/catalog/insert/';
    });

    function fillCatalogModal(catalog) {
        $('#code-cell').text(catalog.code);
        $('#name-cell').text(catalog.name);
        $('#date-cell').text(catalog.date);
        $('#products-tbody').text('');
        for (var i = 0; i < catalog.products.length; i++) {
            $('#products-tbody').append('<tr><td>' + catalog.products[i].code + '</td><td>'
                + catalog.products[i].name + '</td><td>' + catalog.products[i].unit +
                '</td><td>' + catalog.products[i].price + '</td></tr>');
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
});
