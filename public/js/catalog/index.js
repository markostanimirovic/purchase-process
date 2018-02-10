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

    $('.delete').on('click', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda brisanja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da obrišete selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'delete');
        modal.modal('show');
    });

    $('.send').on('click', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda slanja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da pošaljete selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'send');
        modal.modal('show');
    });

    $('.reverse').on('click', function () {
        var id = $(this).attr('data-id');
        modal.find('.modal-title').text('Potvrda storniranja');
        modal.find('.modal-body').text('Da li ste sigurni da želite da stornirate selektovani katalog?');
        modal.find('.confirmed').attr('data-id', id);
        modal.find('.confirmed').attr('action', 'reverse');
        modal.modal('show');
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
                echoErrorMessage(response.message);
            }
        });
    });

    $('.edit').on('click', function () {
        var id = $(this).attr('data-id');
        window.location = '/catalog/edit/' + id;
    });

    function echoErrorMessage(message) {
        $('.error-message')
            .append('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button>' + message + '</div>');
    }
});
