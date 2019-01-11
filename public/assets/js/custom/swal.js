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