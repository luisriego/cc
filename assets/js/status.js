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

function cambiarCor(editableObj) {
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

function deleteRow(e) {
    $(e).removeClass('mdi mdi-delete');
    $(e).addClass('fa fa-spinner');
    $(e).addClass('fa fa-spin');

    var deleteUrl = $(e).data('url');
    var $row = $(this).closest('tr');
    $.ajax({
        url: deleteUrl,
        method: 'DELETE',
        success: function () {
            $row.fadeOut();

            console.log('Y ahora?');
        }
    });

    console.log($row);
}