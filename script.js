document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    const createErrorElement = (input) => {
        const error = document.createElement('div');
        error.className = 'error-message';
        error.style.color = '#800020';
        error.style.fontSize = '0.9rem';
        error.style.marginTop = '5px';
        input.parentNode.insertBefore(error, input.nextSibling);
        return error;
    };
    const validateField = (input, errorMessage) => {
        const error = input.nextElementSibling || createErrorElement(input);
        error.textContent = errorMessage;
        input.classList.toggle('error-input', !!errorMessage);
        return !errorMessage;
    };
    const validateForm = () => {
        let isValid = true;
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            isValid = validateField(email, 'Please enter a valid email address') && isValid;
        } else {
            validateField(email, '');
        }
        const subject = document.getElementById('subject');
        if (subject.value === '') {
            isValid = validateField(subject, 'Please select a subject') && isValid;
        } else {
            validateField(subject, '');
        }
        const message = document.getElementById('message');
        if (message.value.trim().length < 10) {
            isValid = validateField(message, 'Message must be at least 10 characters') && isValid;
        } else {
            validateField(message, '');
        }

        return isValid;
    };
    contactForm.addEventListener('input', (e) => {
        const input = e.target;
        if (input.id === 'name' && input.value.trim().length < 2) {
            validateField(input, 'Please enter a valid name (at least 2 characters)');
        } else if (input.id === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
            validateField(input, 'Please enter a valid email address');
        } else if (input.id === 'subject' && input.value === '') {
            validateField(input, 'Please select a subject');
        } else if (input.id === 'message' && input.value.trim().length < 10) {
            validateField(input, 'Message must be at least 10 characters');
        } else {
            validateField(input, '');
        }
    });
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (validateForm()) {
            contactForm.reset();
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('name').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-z\s'-]/g, '');
    });

    const validateForm = () => {
        let isValid = true;
        const name = document.getElementById('name');
        const nameValue = name.value.trim();
        const namePattern = /^[A-Za-z\s'-]+$/;
        
        if (nameValue.length < 2) {
            isValid = validateField(name, 'Please enter a valid name (at least 2 characters)') && isValid;
        } else if (!namePattern.test(nameValue)) {
            isValid = validateField(name, 'Name can only contain letters, spaces, apostrophes (\'), and hyphens (-). Numbers are not allowed.') && isValid;
        } else {
            validateField(name, '');
        }
    };
    contactForm.addEventListener('input', (e) => {
        const input = e.target;
        if (input.id === 'name') {
            const invalidChars = input.value.match(/[^A-Za-z\s'-]/g);
            if (invalidChars) {
                validateField(input, 'Numbers and special characters are not allowed');
            }
        }
    });
});
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9+]/g, '');
    
    if (this.value.indexOf('+') > 0) {
        this.value = this.value.replace(/\+/g, '');
    }
    const phoneError = document.getElementById('phoneError');
    const isValid = /^\+?\d+$/.test(this.value) && this.value.length >= 10;
    
    if (this.value && !isValid) {
        phoneError.textContent = 'Please enter 10-15 digits only';
        phoneError.style.display = 'block';
    } else {
        phoneError.style.display = 'none';
    }
});
const validateForm = () => {
    let isValid = true;

    const phone = document.getElementById('phone').value;
    const phoneError = document.getElementById('phoneError');
    const phonePattern = /^\+?\d{10,15}$/;
    
    if (phone && !phonePattern.test(phone)) {
        phoneError.textContent = 'Phone must be 10-15 digits (numbers only)';
        phoneError.style.display = 'block';
        isValid = false;
    }
    
    return isValid;
};
