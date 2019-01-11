// Funcion para mostrar 'anterior, proximo, buscar' y el resto de elementos DataTable
$(document).ready(function() {

    $('#mainTable').DataTable();
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });

        // WRAPPER
        var $wrapper = $('.js-wrapper');
        $wrapper.on(
            'submit',
            '.js-new-data-form',
            function (e) {
                e.preventDefault();

                var $form = $(e.currentTarget);
                var $tbody = $wrapper.find('tbody');
                $.ajax({
                    url: $form.get(0).action,
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (data) {
                        $tbody.append(data),
                            confirmar()
                    },
                    error: function (jqXHR) {
                        $form.closest('.js-new-data-form')
                            .html(jqXHR.responseText);
                    }
                });
                // console.log($form.get(0).action);
                // console.log($data);
            }
        );
        // END WRAPPER
    });
});

async function cambiarAccion(editableObj, valorAtual) {
    const {value: valorSel} = await swal({
        title: 'Selecione uma nova opção',
        input: 'select',
        inputOptions: {
            1: 'Ativo, Tramitando',
            0: 'Inativo/Encerrado',
            2: 'Rejeitado'
        },
        inputValue: valorAtual,
        // inputPlaceholder: 'Selecione uma opção',
        showCancelButton: true
    })
    if (valorSel) {
        $.ajax({
            url: $(editableObj).data('url'),
            type: "PUT",
            data: {"statusVal": valorSel},
            statusCode: {
                403: function(xhr) {
                    swal({
                        type: 'error',
                        title: 'Ação não permitida',
                        html:
                        '<pre>Nem o Status Aberto e nem o Finalizado, <br>podem ser mudados! </pre>' +
                        'Status: '+xhr.status,
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Close!'
                    })
                }
            },
            success: function () {
                var statusType = '';
                var statusBackground = '';

                switch(valorSel) {
                    case '0':
                        statusType = 'Encerrado';
                        statusBackground = 'text-center text-white label-success';
                        break;
                    case '1':
                        statusType = 'Ativo';
                        statusBackground = 'text-center text-white label-danger';
                        break;
                    case '2':
                        statusType = 'Reprovado';
                        statusBackground = 'text-center text-white label-warning';
                        break;
                    default:
                        statusType = 'Ativo';
                        statusBackground = 'text-center text-white label-danger';
                }

                $(editableObj).html(statusType);
                $(editableObj).attr( "class", statusBackground );
                confirmar();
            }
        });
    }
}

async function cambiarColor(editableObj) {
    var cor = $(editableObj).find('option:selected').val();
    $.ajax({
        url: $(editableObj).data('url'),
        type: "PUT",
        data: {"cor": cor},
        success: function (data) {
            $(editableObj).css("background-color", cor);
            confirmar();
        },
        error: function(xhr){
            error(xhr);
        }
    });
}

async function cambiarNombre(editableObj) {
    var name = $(editableObj).html().trim();
    $.ajax({
        url: $(editableObj).data('url'),
        type: "PUT",
        data: {"name": name},
        success: function (data) {
            confirmar();
        },
        error: function(xhr){
            error(xhr);
        }
    });
}

async function deleteRow(e) {
    $(e).removeClass('mdi mdi-delete');
    $(e).addClass('fa fa-spinner');
    $(e).addClass('fa fa-spin');

    var deleteUrl = $(e).data('url');
    var $row = $(e).closest('tr');
    $.ajax({
        url: deleteUrl,
        method: 'DELETE',
        success: function () {
            $row.fadeOut();
        }
    });
}

function confirmar() {
    swal({
        type: 'success',
        title: 'Cambio Guardado!',
        html: 'Tudo saiu como planejado.',
        showCancelButton: false,
        timer: 1500
    })
}

function error(xhr) {
    swal({
        type: 'error',
        title: 'Shiiii...',
        html:
        '<pre>Alguma coisa deu errado! </pre>' +
        'Status: '+xhr.status,
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Close!',
        timer: 2000
    })
}
