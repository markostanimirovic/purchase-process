$(document).ready(function () {
    $('.cancel').on('click', function () {
        window.location = '/orderForm/';
    });

    $('#date').datepicker({
        onSelect: function () {
            $('#date-error').text('');
        },
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        dayNamesMin: ["Ned", "Pon", "Uto", "Sre", "Čet", "Pet", "Sub"],
        monthNames: ["Januar", "Februar", "Mart", "April", "Maj", "Jun", "Jul", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"]
    });

    $('#supplier').select2({
        width: '100%',
        allowClear: true,
        minimumInputLength: 2,
        multiple: false,
        placeholder: "Dobavljač",
        ajax: {
            url: '/supplier/getAllSuppliersByFilter/',
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    filter: params.term
                };
            }
        }
    });

    $('#catalog').select2({
        width: '100%',
        allowClear: true,
        multiple: false,
        placeholder: "Katalog"
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

    var spinner = $('#quantity').spinner({
        min: 1
    }).val(1);

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

    $('#supplier').on('select2:select', function () {
        $('#catalog').text('');
        var id = this.value;
        $.get('/catalog/getAllBySupplier/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == "success") {
                populateCatalogSelect(response.data);
            } else {

            }
        });
    });

    $('#supplier').on('select2:unselect', function () {
        $('#catalog').text('');
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

    $('#date').on('focusout', function () {
        isDateValidate($(this).val());
    });

    $('#catalog-select-div').on('focusout', function () {
        isCatalogValidate($('#catalog').val());
    });

    $('#supplier-select-div').on('focusout', function () {
        isSupplierValidate($('#supplier').val());
    });

    $('.save').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate()) {
            return;
        }
        sendDataToTheServer('insertDraft');
    });

    $('.send').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate()) {
            return;
        }
        sendDataToTheServer('insertSent');
    });

    function populateCatalogSelect(catalogs) {
        var opt = document.createElement('option');
        opt.setAttribute('type', 'hidden');
        $('#catalog').append(opt);
        $.each(catalogs, function (i, catalog) {
            var opt = document.createElement('option');
            opt.value = catalog.id;
            opt.innerHTML = catalog.code + ' ' + catalog.name;
            $('#catalog').append(opt);
        });
    }

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
                if (response.type == 'success') {
                    window.location = '/catalog/';
                } else {
                    echoErrorMessages(response.messages);
                }
            });
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
        } else if (code.length > 20) {
            $('#code-error').text('Maksimalan broj karaktera za šifru je 20.');
            return false;
        }
        $('#code-error').text('');
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

    function isSupplierValidate(supplier) {
        if (supplier == undefined || supplier == "") {
            $('#supplier-error').text('Izaberite dobavljača.');
            return false;
        }
        $('#supplier-error').text('');
        return true;
    }

    function isCatalogValidate(catalog) {
        if (catalog == undefined || catalog == "") {
            $('#catalog-error').html('Izaberite katalog. <span class="text-primary">Napomena: Prvo morate izabrati dobavljača.</span>');
            return false;
        }
        $('#catalog-error').text('');
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