function previewImage(event) {
    const image = document.getElementById('image-preview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            image.style.display = 'block';
            image.style.width = '150px';
            image.style.height = '150px';
            image.style.borderRadius = '50%'; 
        };
        reader.readAsDataURL(file);
    } else {
        image.style.display = 'none';
    }
}


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