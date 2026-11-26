// Funciones comunes que usan todas las páginas

// Evita que un texto con < > se interprete como HTML al mostrarlo en la tabla
function esc(texto) {
    return $('<div>').text(texto == null ? '' : String(texto)).html();
}

// ---------------------------------------------------------------------
// Ventanas emergentes centradas (modal de Bootstrap) para todo el sistema
// ---------------------------------------------------------------------
var TIPOS_MENSAJE = {
    success: { titulo: 'Listo', clase: 'bg-success text-white' },
    danger:  { titulo: 'Error', clase: 'bg-danger text-white' },
    warning: { titulo: 'Atención', clase: 'bg-warning' },
    info:    { titulo: 'Aviso', clase: 'bg-primary text-white' }
};

// Crea (una sola vez) la ventana centrada que usan todos los mensajes
function ventanaMensaje() {
    var $v = $('#dlgMensaje');
    if ($v.length === 0) {
        $v = $('<div class="modal fade" id="dlgMensaje" tabindex="-1" data-backdrop="static" style="z-index:1060;">' +
            '<div class="modal-dialog modal-dialog-centered"><div class="modal-content">' +
            '<div class="modal-header"><h5 class="modal-title"></h5>' +
            '<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>' +
            '<div class="modal-body"></div>' +
            '<div class="modal-footer"></div>' +
            '</div></div></div>');
        $('body').append($v);
        // Si había otra ventana abierta debajo (Nuevo, Editar…), se mantiene bien
        $v.on('shown.bs.modal', function () {
            $('.modal-backdrop').last().css('z-index', 1055);
        });
        $v.on('hidden.bs.modal', function () {
            if ($('.modal.show').length > 0) { $('body').addClass('modal-open'); }
        });
    }
    return $v;
}

// Abre la ventana; si otra ventana se está cerrando, espera un momento
function abrirVentanaMensaje($v) {
    var ocupada = false;
    $('.modal').not($v).each(function () {
        var m = $(this).data('bs.modal');
        if (m && m._isTransitioning) { ocupada = true; }
    });
    if (ocupada) {
        setTimeout(function () { abrirVentanaMensaje($v); }, 150);
    } else {
        $v.modal('show');
    }
}

// Muestra un mensaje en una ventana centrada (tipo: success, danger, warning, info)
function aviso(texto, tipo) {
    var t = TIPOS_MENSAJE[tipo] || TIPOS_MENSAJE.info;
    var $v = ventanaMensaje();
    $v.find('.modal-header').attr('class', 'modal-header ' + t.clase);
    $v.find('.modal-title').text(t.titulo);
    $v.find('.close').toggleClass('text-white', t.clase.indexOf('text-white') !== -1);
    $v.find('.modal-body').html(esc(texto).replace(/\n/g, '<br>'));
    $v.find('.modal-footer').html('<button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>');
    $v.off('click.si');
    abrirVentanaMensaje($v);
}

// Los alert() del sistema también salen en la ventana centrada
window.alert = function (texto) {
    aviso(texto, 'warning');
};

// Pregunta Sí / Cancelar en la ventana centrada; si acepta, ejecuta siAcepta()
function confirmar(texto, siAcepta) {
    var $v = ventanaMensaje();
    $v.find('.modal-header').attr('class', 'modal-header ' + TIPOS_MENSAJE.info.clase);
    $v.find('.modal-title').text('Confirmar');
    $v.find('.close').addClass('text-white');
    $v.find('.modal-body').html(esc(texto));
    $v.find('.modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>' +
        '<button type="button" class="btn btn-primary" id="btnConfirmarSi">Sí</button>');
    $v.off('click.si').on('click.si', '#btnConfirmarSi', function () {
        $v.off('click.si');
        $v.one('hidden.bs.modal', function () { siAcepta(); });
        $v.modal('hide');
    });
    abrirVentanaMensaje($v);
}

// Si el servidor responde 401, la sesión terminó: volver al inicio de sesión
$(document).ajaxError(function (evento, xhr) {
    if (xhr.status === 401) {
        location.href = 'index.php';
    } else {
        aviso('No se pudo comunicar con el servidor. Revise que XAMPP esté encendido.', 'danger');
    }
});