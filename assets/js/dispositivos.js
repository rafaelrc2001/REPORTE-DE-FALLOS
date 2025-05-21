 // dispositivos.js - Funcionalidades para el módulo de dispositivos

// Función para validar el formulario de registro de dispositivos
function validarFormularioRegistro() {
    const formulario = document.getElementById('formulario-registro');
    const nombre = document.getElementById('nombre');
    const tipo = document.getElementById('tipo');
    const fecha = document.getElementById('fecha');
    const estado = document.getElementById('estado');
    const ubicacion = document.getElementById('ubicacion');

    formulario.addEventListener('submit', function (event) {
        let valid = true;

        // Validar campo "Nombre"
        if (nombre.value.trim() === '') {
            alert('El campo "Nombre del Dispositivo" es obligatorio.');
            valid = false;
        }

        // Validar campo "Tipo"
        if (tipo.value === '') {
            alert('Debes seleccionar un tipo de dispositivo.');
            valid = false;
        }

        // Validar campo "Fecha"
        if (fecha.value === '') {
            alert('El campo "Fecha de Adquisición" es obligatorio.');
            valid = false;
        }

        // Validar campo "Estado"
        if (estado.value === '') {
            alert('Debes seleccionar un estado.');
            valid = false;
        }

        // Validar campo "Ubicación"
        if (ubicacion.value.trim() === '') {
            alert('El campo "Ubicación" es obligatorio.');
            valid = false;
        }

        // Si no es válido, detener el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    });
}

// Función para confirmar la eliminación de un dispositivo
function confirmarEliminacion() {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar');

    botonesEliminar.forEach(function (boton) {
        boton.addEventListener('click', function (event) {
            if (!confirm('¿Estás seguro de que deseas eliminar este dispositivo?')) {
                event.preventDefault(); // Detener la acción si el usuario cancela
            }
        });
    });
}

// Función para cargar dinámicamente los detalles de un dispositivo
function cargarDetallesDispositivo() {
    const enlacesDetalles = document.querySelectorAll('.btn-ver-detalles');

    enlacesDetalles.forEach(function (enlace) {
        enlace.addEventListener('click', function (event) {
            event.preventDefault(); // Evitar que el enlace redirija

            const idDispositivo = this.getAttribute('data-id');
            fetch(`/fallos_itesco/modulos/dispositivos/detalles.php?id=${idDispositivo}`)
                .then(response => response.text())
                .then(data => {
                    // Mostrar los detalles en un modal o en una sección de la página
                    document.getElementById('detalles-dispositivo').innerHTML = data;
                })
                .catch(error => console.error('Error al cargar los detalles:', error));
        });
    });
}

// Función para inicializar todas las funcionalidades
function inicializar() {
    if (document.getElementById('formulario-registro')) {
        validarFormularioRegistro(); // Validar formulario de registro
    }

    if (document.querySelectorAll('.btn-eliminar').length > 0) {
        confirmarEliminacion(); // Confirmar eliminación de dispositivos
    }

    if (document.querySelectorAll('.btn-ver-detalles').length > 0) {
        cargarDetallesDispositivo(); // Cargar detalles dinámicamente
    }
}

// Ejecutar la inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', inicializar);
