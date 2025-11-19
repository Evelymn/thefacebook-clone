// assets/js/validation.js

function validateUniversityEmail(email) {
    const allowedDomains = ['uvg.edu.gt', 'est.uvg.edu.gt'];
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailPattern.test(email)) {
        return false;
    }
    
    const domain = email.split('@')[1].toLowerCase();
    return allowedDomains.includes(domain);
}

function validateRegisterForm() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (name.length < 3) {
        alert('El nombre debe tener al menos 3 caracteres.');
        return false;
    }
    
    if (!validateUniversityEmail(email)) {
        alert('Debes usar un correo universitario válido de UVG (@uvg.edu.gt o @est.uvg.edu.gt).');
        return false;
    }
    
    if (password.length < 6) {
        alert('La contraseña debe tener al menos 6 caracteres.');
        return false;
    }
    
    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return false;
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            
            if (email && !validateUniversityEmail(email)) {
                this.style.borderColor = '#dc3545';
                
                let errorMsg = this.nextElementSibling;
                if (!errorMsg || !errorMsg.classList.contains('error-msg')) {
                    errorMsg = document.createElement('small');
                    errorMsg.classList.add('error-msg');
                    errorMsg.style.color = '#dc3545';
                    this.parentNode.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Debe ser un correo universitario UVG válido';
            } else if (email) {
                this.style.borderColor = '#28a745';
                const errorMsg = this.parentNode.querySelector('.error-msg');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        emailInput.addEventListener('input', function() {
            this.style.borderColor = '';
            const errorMsg = this.parentNode.querySelector('.error-msg');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    }
});
