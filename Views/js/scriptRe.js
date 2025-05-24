document.getElementById('file').addEventListener('change', function (event) {
    console.log('Evento de cambio de archivo detectado');
    if (event.target.files && event.target.files[0]) {
        var file = event.target.files[0];
        var fileName = file.name;
        var input = document.getElementById('imagen');
        input.value = fileName;
        console.log('Nombre del archivo:', fileName);
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
            console.log('Imagen cargada:', e.target.result);
        };  
        reader.onerror = function (e) {
            console.error('Error al leer el archivo:', e.target.error);
        };
        reader.readAsDataURL(file);
    } else {
        alert('No se seleccionó ningún archivo.');
    }
});

document.getElementById('registro').addEventListener('submit', function(event) {
    event.preventDefault();
    document.getElementById('error').textContent = '';

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmpass').value.trim();

    let valid = true;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            document.getElementById('error').textContent = 'Ingresa un correo electrónico válido.';
            valid = false;
        }

    const passwordPattern = /(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d)(?=.*[^A-Za-z\d])\S{8,}/

        if (!passwordPattern.test(password)) {
            document.getElementById('error').textContent = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial.';
            valid = false;
        }

        if (password !== confirmPassword) {
            document.getElementById('error').textContent = 'Las contraseñas no coinciden.';
            valid = false;
        }

        if (valid) {
            document.querySelector('form').submit()
        }



});