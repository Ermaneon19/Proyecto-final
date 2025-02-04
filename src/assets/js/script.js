document.addEventListener('DOMContentLoaded', function() {
    // Verificar si la URL contiene el parámetro success=true
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');

    if (success === 'true') {
        const successModal = document.getElementById('successModal');
        successModal.style.display = "block"; // Mostrar el modal de éxito

        // Función para cerrar el modal
        const closeModal = function() {
            successModal.style.display = "none"; // Ocultar el modal
        };

        // Botón de cerrar dentro del modal
        const closeBtn = document.querySelector('.close-btn');
        closeBtn.addEventListener('click', closeModal);

        // Botón "Cerrar" adicional fuera del modal
        const closeModalBtn = document.getElementById('closeModalBtn');
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Mostrar modal de error si existe el parámetro error
    if (error) {
        const errorModal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.innerText = decodeURIComponent(error); // Mostrar el mensaje de error

        errorModal.style.display = "block"; // Mostrar el modal de error

        // Función para cerrar el modal de error
        const closeErrorModal = function() {
            errorModal.style.display = "none"; // Ocultar el modal de error
        };

        // Botón de cerrar dentro del modal de error
        const closeErrorBtn = document.querySelector('.close-btn');
        closeErrorBtn.addEventListener('click', closeErrorModal);

        // Botón "Cerrar" adicional fuera del modal
        const closeErrorModalBtn = document.getElementById('closeErrorModalBtn');
        closeErrorModalBtn.addEventListener('click', closeErrorModal);
    }
});

$(document).ready(function() {
    $(".Select2").select2({
        placeholder: "Selecciona una opción",
        allowClear: true,
        width: '100%'
    });
});

