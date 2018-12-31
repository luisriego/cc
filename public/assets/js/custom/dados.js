/**
 * Created by luis on 19/02/18.
 */

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
    });
});


function saveToDatabase(editableObj,id, valorOriginal) {
    var valorNuevo = $.trim(editableObj.innerHTML);
    valorOriginal = $.trim(valorOriginal);
    if (valorOriginal !== valorNuevo) {
        var columna = editableObj.attributes["name"].value;
        var entidad = getEntidad(editableObj.attributes["data-entity"].value);
        var url = URLapi+'editar_dados/'+id+'/'+columna+'/'+valorNuevo+'/'+entidad;
        console.log(url);
        $.ajax({
            url: url,
            type: "PUT",
            success: function (xhr) {
                confirmar();
                location.reload();
            },
            error: function(xhr){
                error(xhr);
            }
        })

    }
}

function ColourSelected(editableObj,id) {
    var seleccionado = $(editableObj).find('option:selected').text();
    var clave = $(editableObj).find('option:selected').val();
    var valorNuevo = myTrim(editableObj.innerHTML);
    var columna = editableObj.attributes["name"].value;
    $(editableObj).css("background-color",clave);
    var url = URLapi+'editar_status/'+id+'/'+columna+'/'+clave;
    console.log(url);
    $.ajax({
        url: url,
        type: "PUT",
        success: function () {
            confirmar();
        },
        error: function(xhr){
            error(xhr);
        }
    });
}

function confirmar() {
    swal({
        type: 'success',
        title: 'Cambio Guardado!',
        html: 'Tudo saiu como planejado.',
        showCancelButton: false,
        timer: 2000
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

async function ativo(id, valorAtual) {
    const {value: valorSel} = await swal({
        title: 'Selecione uma nova opção',
        input: 'select',
        inputOptions: {
            'true': 'Ativo, Tramitando',
            'false': 'Inativo/Encerrado',
            'null': 'Rejeitado'
        },
        inputValue: valorAtual,
//               inputPlaceholder: 'Selecione uma opcao',
        showCancelButton: true
    })
    if (valorSel) {
        var columna = 'ativo';
        var url = URLapi+'editar_status/'+id+'/'+ columna+'/'+valorSel;
        $.ajax({
            url: url,
            type: "PUT",
            statusCode: {
                401: function(xhr) {
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
            success: function (xhr) {
                swal({
                        type: 'success',
                        title: 'Cambio Guardado!',
                        html: 'Tudo saiu como planejado.',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Close!',
                        showLoaderOnConfirm: true //,
                        // allowOutsideClick: () => !swal.isLoading()
            })
                location.reload();
            } //,
                   // error: function(){
                   //     swal({
                   //         type: 'error',
                   //         title: 'Shiiii...',
                   //         html: 'Alguma coisa deu errado, nada feito! ',
                   //         showCancelButton: false,
                   //         confirmButtonColor: '#3085d6',
                   //         confirmButtonText: 'Close!'
                   //     })
                   // }
        });
    }
}

async function prioridade(editableObj,id,valorAtual) {
    console.log(editableObj);
    const {value: valorSel} = await swal({
        title: 'Selecione a Prioridade desejada',
        input: 'select',
        inputOptions: {
            0: 'Nenhuma urgencia',
            1: 'Pouco Urgente',
            2: 'Prioritario',
            3: 'Urgente',
            4: 'Muito Urgente',
            5: 'Emergencia'
        },
        inputValue: valorAtual,
        inputPlaceholder: 'Selecione uma opcao',
        showCancelButton: true
    })
    if (valorSel) {
        var columna = 'prioridade';
        var entidad = 'App:Defeito';
        var url = URLapi+'editar_dados/'+id+'/'+columna+'/'+valorSel+'/'+entidad;
        // alert(url);
        $.ajax({
            url: url,
            type: "PUT",
            success: function (xhr) {
                swal({
                    type: 'success',
                    title: 'Cambio Guardado!',
                    html: 'Tudo saiu como planejado.',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Close!',
                    showLoaderOnConfirm: true //,
                    // allowOutsideClick: () => !swal.isLoading()
                })
                location.reload();
            },
            error: function(){
                swal({
                    type: 'error',
                    title: 'Shiiii...',
                    html: 'Alguma coisa deu errado, nada feito! ',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Close!'
                })
            }
        });
    }
}

// Funcion para seleccionar la entidad adecuada en el metodo 'saveToDatabase()'
function getEntidad(titulo) {
    switch(titulo) {
        case "roteador":
            var entity = 'App:Roteador';
            break;
        case "status":
            var entity = 'App:Status';
            break;
        case "internet":
            var entity = 'App:Internet';
            break;
        case "defeito":
            var entity = 'App:Defeito';
            break;
        case "estação":
            var entity = 'App:TipoEstacao';
            break;
        case "impressora":
            var entity = 'App:Impressora';
            break;
        case "servidor":
            var entity = 'App:Servidor';
            break;
        case "sistemas":
            var entity = 'App:Sistema';
            break;
        case "vserve":
            var entity = 'App:VServe';
            break;
    }
    
    return entity;
}

function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}