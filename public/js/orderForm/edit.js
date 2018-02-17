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
        placeholder: {
            id: '-1',
            text: 'Katalog'
        }
    });

    var productSelect = $('#product').select2({
        width: '100%',
        multiple: false,
        placeholder: 'Proizvod'
    });

    var table = $('#tableData').DataTable({
        dom: "<'myfilter'f><'mylength'l>t",
        info: false,
        paging: false,
        columnDefs: [
            {targets: -1, orderable: false}
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

    $('#tableData a').editable({
        mode: 'inline',
        type: 'text',
        step: '1',
        min: '1',
        inputclass: 'editable-field',
        validate: function (value) {
            if (value == '') {
                return 'Unesite količinu.';
            }
            if (!(/^\d+$/.test(value))) {
                return 'Količina mora biti pozitivan ceo broj.';
            }

            if (value == 0) {
                return 'Količina mora biti broj veći od nule.'
            }

            var price = $(this).attr('data-price');
            var rowForEdit = table.row($(this).parents('tr'));
            table.cell({row: rowForEdit.index(), column: 5}).data((price * value).toFixed(2)).draw();
            calculateTotalAmount();
        }
    });

    var spinner = $('#quantity').spinner({
        min: 1
    }).val(1);

    $('.add').on('click', function () {
        var id = $('#product').val();

        $('#product-error').text('');
        if (id == undefined) {
            $('#product-error').html('Izaberite proizvod. <span class="text-primary">Napomena: Prvo morate izabrati katalog.</span>');
            return;
        }
        if (table.rows('[id=' + id + ']').any()) {
            $('#product-error').text('Proizvod već postoji u stavkama narudžbenice.');
            return;
        }

        var code = $('#product').select2().find(':selected').data('code');
        var name = $('#product').select2().find(':selected').data('name');
        var unit = $('#product').select2().find(':selected').data('unit');
        var price = $('#product').select2().find(':selected').data('price');
        var quantity = '<a href="#" data-price="' + price + '">' + spinner.spinner('value') + '</a>';
        var amount = $('#amount').val();
        var delButton = '<button type="button" class="delete-row btn btn-danger">' +
            '<i class="fa fa-trash-o" aria-hidden="true"></i></button>';

        if (table.rows().count() == 0) {
            $('#tableDiv').show();
        }
        table.row.add([code, name, unit, price.toFixed(2), quantity, amount, delButton]).node().id = id;
        table.draw(false);

        var totalAmount = parseFloat($('#total-amount').val());
        var parsedAmount = parseFloat(amount);
        $('#total-amount').val((totalAmount + parsedAmount).toFixed(2));

        setEditable();

    });

    $.fn.editableform.buttons =
        '<button type="submit" title="Potvrdi" class="submit-edit btn btn-primary editable-submit" style="height: 36.8px;"><i class="fa fa-check" aria-hidden="true"></i></button>' +
        '<button type="button" title="Odustani" class="btn btn-secondary editable-cancel" style="height: 36.8px;"><i class="fa fa-times" aria-hidden="true"></i></button>';

    $.fn.editable.defaults.onblur = 'submit';


    function setEditable() {
        $('#tableData a').editable({
            mode: 'inline',
            type: 'text',
            step: '1',
            min: '1',
            inputclass: 'editable-field',
            validate: function (value) {
                if (value == '') {
                    return 'Unesite količinu.';
                }
                if (!(/^\d+$/.test(value))) {
                    return 'Količina mora biti pozitivan ceo broj.';
                }

                if (value == 0) {
                    return 'Količina mora biti broj veći od nule.'
                }

                var price = $(this).attr('data-price');
                var rowForEdit = table.row($(this).parents('tr'));
                table.cell({row: rowForEdit.index(), column: 5}).data((price * value).toFixed(2)).draw();
                calculateTotalAmount();
            }
        });
    }

    function calculateTotalAmount() {
        var sum = 0;
        table.rows().every(function (rowId) {
            sum += parseFloat(table.cell(rowId, 5).data());
        });
        $('#total-amount').val(sum.toFixed(2));
    }

    $('#supplier').on('select2:select', function () {
        $('#supplier-error').text('');
        $('#catalog').empty();
        var opt = document.createElement('option');
        opt.value = -1;
        $('#catalog').append(opt);

        productSelect.empty();
        productSelect = $('#product').select2({
            width: '100%',
            multiple: false,
            placeholder: 'Proizvod'
        });

        $('#unit').val('');
        $('#price').val('');
        $('#quantity').val(1);
        $('#amount').val(0);
        $('#total-amount').val(0);

        var id = this.value;
        $.get('/catalog/getAllBySupplier/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == 'success') {
                populateCatalogSelect(response.data);
            } else {
                echoErrorMessage(response.message);
            }
        });
    });

    $('#supplier').on('select2:unselect', function () {
        $('#catalog').empty();
        var opt = document.createElement('option');
        opt.value = -1;
        $('#catalog').append(opt);

        productSelect.empty();
        productSelect = $('#product').select2({
            width: '100%',
            multiple: false,
            placeholder: 'Proizvod'
        });

        $('#unit').val('');
        $('#price').val('');
        $('#quantity').val(1);
        $('#amount').val(0);
        $('#total-amount').val(0);

        table.clear().draw();
        $('#tableDiv').hide();
    });

    $('#catalog').on('select2:select', function () {
        $('#catalog-error').text('');

        productSelect.empty();
        productSelect = $('#product').select2({
            width: '100%',
            multiple: false,
            placeholder: 'Proizvod'
        });

        $('#unit').val('');
        $('#price').val('');
        $('#quantity').val(1);
        $('#amount').val(0);
        $('#total-amount').val(0);

        table.clear().draw();
        $('#tableDiv').hide();
        var id = this.value;
        $.get('/product/getAllByCatalog/' + id, function (data) {
            var response = JSON.parse(data);
            if (response.type == 'success') {
                populateProductSelect(response.data);
            } else {
                echoErrorMessage(response.message);
            }
        });
    });

    $('#catalog').on('select2:unselect', function () {
        productSelect.empty();
        productSelect = $('#product').select2({
            width: '100%',
            multiple: false,
            placeholder: 'Proizvod'
        });

        $('#unit').val('');
        $('#price').val('');
        $('#quantity').val(1);
        $('#amount').val(0);
        $('#total-amount').val(0);

        table.clear().draw();
        $('#tableDiv').hide();
    });

    $('#product').on('select2:select', function () {
        var unit = $('#product').select2().find(':selected').data('unit');
        var price = $('#product').select2().find(':selected').data('price');
        $('#unit').val(unit);
        $('#price').val(price.toFixed(2));
        $('#amount').val(price.toFixed(2));
        $('#quantity').val(1);
    });

    $("#quantity").spinner({
        spin: function (event, ui) {
            var amount = $('#amount').val();
            var price = $('#price').val();
            if (amount > 0) {
                $('#amount').val((ui.value * price).toFixed(2));
            }
        }
    });

    $('#tableDiv').on('click', '.delete-row', function () {
        table.row($(this).parents('tr')).remove().draw();
        if (table.rows().count() == 0) {
            $('#total-amount').val(0);
            $('#tableDiv').hide();
        } else {
            calculateTotalAmount();
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

    function populateProductSelect(products) {
        $.each(products, function (i, product) {
            if (i == 0) {
                $('#unit').val(product.unit);
                $('#price').val(product.price.toFixed(2));
                $('#amount').val(product.price.toFixed(2));
            }
            var opt = document.createElement('option');
            opt.value = product.id;
            opt.innerHTML = product.code + ' ' + product.name;
            opt.setAttribute('data-code', product.code);
            opt.setAttribute('data-name', product.name);
            opt.setAttribute('data-price', product.price);
            opt.setAttribute('data-unit', product.unit);
            $('#product').append(opt);
        });
    }

    function populateCatalogSelect(catalogs) {
        $.each(catalogs, function (i, catalog) {
            var opt = document.createElement('option');
            opt.value = catalog.id;
            opt.innerHTML = catalog.code + ' ' + catalog.name;
            $('#catalog').append(opt);
        });
    }

    $('.save').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate() |
            !isSupplierValidate($('#supplier').val()) | !isCatalogValidate($('#catalog').val())) {
            return;
        }
        sendDataToTheServerDraft('insertDraft');
    });

    $('.send').on('click', function () {
        if (!isCodeValidate($('#code').val()) | !isDateValidate($('#date').val()) | !isTableValidate() |
            !isSupplierValidate($('#supplier').val()) | !isCatalogValidate($('#catalog').val())) {
            return;
        }
        sendDataToTheServerDraft('insertSent');
    });

    function sendDataToTheServerDraft(method) {
        $.post('/orderForm/' + method,
            {
                "orderForm": {
                    "code": $('#code').val(),
                    "date": $('#date').val(),
                    "supplierId": $('#supplier').val(),
                    "catalogId": $('#catalog').val(),
                    "items": getArrayOfItems()
                }
            }, function (data) {
                var response = JSON.parse(data);
                if (response.type == 'success') {
                    window.location = '/orderForm/';
                } else {
                    echoErrorMessages(response.messages);
                }
            });
    }

    function getArrayOfItems() {
        var tbl = document.getElementById('tableData');
        var items = Array();
        var rowLength = tbl.rows.length;
        var j = 0;
        for (var i = 1; i < rowLength; i++) {
            item = new Object();
            var row = tbl.rows[i];
            item.productId = $(row).attr('id');
            item.quantity = $(row.cells[4]).html().replace(/(<([^>]+)>)/ig, "").trim();
            items[j] = item;
            j++;
        }
        return items;
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
        $('.error-messages')
            .html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button>' + message + '</div>');
    }

    function isCodeValidate(code) {
        if (code == undefined || code.trim() == "") {
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
        if (catalog == -1) {
            $('#catalog-error').html('Izaberite katalog. <span class="text-primary">Napomena: Prvo morate izabrati dobavljača.</span>');
            return false;
        }
        $('#catalog-error').text('');
        return true;
    }

    function isTableValidate() {
        if (table.rows().count() == 0) {
            $('#product-error').text('Narudžbenica mora imati najmanje jednu stavku.');
            return false;
        }
        $('#product-error').text('');
        return true;
    }
});