// Funciones comunes que usan todas las páginas

// Evita que un texto con < > se interprete como HTML al mostrarlo en la tabla
function esc(texto) {
    return $('<div>').text(texto == null ? '' : String(texto)).html();
}

// Muestra un mensaje arriba de la página (tipo: success, danger, warning, info)
function aviso(texto, tipo) {
    var html = '<div class="alert alert-' + (tipo || 'info') + ' alert-dismissible fade show">' +
        esc(texto) +
        '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
    $('#avisos').html(html);
    window.scrollTo(0, 0);
}
