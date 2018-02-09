$(document).ready(function () {
    $('.cancel').on('click', function () {
        window.location = '/catalog/';
    });

    $('#date').datepicker({
        onSelect: function () {
            $('#date-error').text('');
        },
        altFormat: "dd-mm-yyyy",
        firstDay: 1,
        dayNamesMin: ["Ned", "Pon", "Uto", "Sre", "Čet", "Pet", "Sub"],
        monthNames: ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"]
    });

    $('#product').select2({
        width: '100%',
        allowClear: true,
        multiple: false,
        placeholder: "Proizvod"
    });

    var table = $('#tableData').DataTable({
        dom: "<'myfilter'f><'mylength'l>t",
        info: false,
        paging: false,
        columnDefs: [
            {orderable: false, targets: -1}
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

    $('.add').on('click', function () {
        var code = $('#product').find(':selected').val();

        if (code == "" || code == undefined) {
            $('#product-error').text('Niste izabrali proizvod.');
            return;
        }

        var exists = false;
        table.rows().every(function (rowId) {
            if (table.cell(rowId, 0).data() == code) {
                $('#product-error').text('Izabrani proizvod već postoji u katalogu.');
                exists = true;
                return;
            }
        });
        if (exists == true) {
            return;
        }

        $('#product-error').text('');
        $.get('/product/getProductByCode/' + code, function (product) {
            if (product.code == undefined) {
                $('#product-error').text('Izabrani proizvod ne postoji.');
            } else {
                if (table.rows().count() == 0) {
                    $('#tableDiv').show();
                }
                table.row.add([
                    product.code, product.name, product.unit, product.price,
                    '<button type="button" class="delete-row btn btn-danger" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>'
                ]).draw(false);
            }
        });
    });

    $('#tableDiv').on('click', '.delete-row', function () {
        table.row($(this).parents('tr')).remove().draw();
        if (table.rows().count() == 0) {
            $('#tableDiv').hide();
        }
    });

    $('#code').on('focusout', function () {
        isCodeValidate($(this).val());
    });

    $('#name').on('focusout', function () {
        isNameValidate($(this).val());
    });

    $('#date').on('focusout', function () {
        isDateValidate($(this).val());
    });

    $('.save').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate() | !isNameValidate($('#name').val())) {
            return;
        }

        sendDataToTheServer('save');
    });

    $('.send').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate() | !isNameValidate($('#name').val())) {
            return;
        }

        sendDataToTheServer('send');
    });

    function sendDataToTheServer(method) {
        $.post('/catalog/' + method,
            {
                "catalog": {
                    "code": $('#code').val(),
                    "name": $('#name').val(),
                    "date": $('#date').val(),
                    "productCodes": getArrayOfCodes()
                }
            }, function (data) {
                var response = JSON.parse(data);
                console.log(response.message);
            });
    }

    function getArrayOfCodes() {
        var codes = Array();
        var i = 0;

        table.rows().every(function (rowId) {
            codes[i] = table.cell(rowId, 0).data();
            i++;
        });

        return codes;
    }

    function isCodeValidate(code) {
        if (code == undefined || code == "") {
            $('#code-error').text('Šifra ne sme da bude prazno polje.');
            return false;
        } else if (code.length > 10) {
            $('#code-error').text('Maksimalan broj karaktera za šifru je 10.');
            return false;
        }
        $('#code-error').text('');
        return true;
    }

    function isNameValidate(name) {
        if (name == undefined || name == "") {
            $('#name-error').text('Naziv ne sme da bude prazno polje.');
            return false;
        } else if (name.length > 50) {
            $('#name-error').text('Maksimalan broj karaktera za naziv je 50.');
            return false;
        }
        $('#name-error').text('');
        return true;
    }

    function isDateValidate(date) {
        if (date == undefined || date == "") {
            $('#date-error').text('Izaberite datum.');
            return false;
        }
        $('#date-error').text('');
        return true;
    }

    function isTableValidate() {
        if (table.rows().count() == 0) {
            $('#product-error').text('Katalog mora imati najmanje jedan proizvod.');
            return false;
        }
        $('#product-error').text('');
        return true;
    }
});
