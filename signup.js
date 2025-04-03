document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission
  
    // Get form inputs
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    const accountType = document.getElementById('accountType').value;
  
    // Get error message elements
    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const accountTypeError = document.getElementById('accountTypeError');
  
    // Reset error messages
    nameError.style.display = 'none';
    emailError.style.display = 'none';
    passwordError.style.display = 'none';
    confirmPasswordError.style.display = 'none';
    accountTypeError.style.display = 'none';
  
    // Validation flags
    let isNameValid = false;
    let isEmailValid = false;
    let isPasswordValid = false;
    let isConfirmPasswordValid = false;
    let isAccountTypeValid = false;
  
    // Validate Name (characters only)
    const nameRegex = /^[A-Za-z\s]+$/;
    if (nameRegex.test(name)) {
      isNameValid = true;
    } else {
      nameError.textContent = 'Name must contain only letters and spaces.';
      nameError.style.display = 'block';
    }
  
    // Validate Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailRegex.test(email)) {
      isEmailValid = true;
    } else {
      emailError.textContent = 'Please enter a valid email address.';
      emailError.style.display = 'block';
    }
  
    // Validate Password (must contain uppercase, lowercase, numbers, and special characters)
    const passwordRegex = /^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[!@#$%^&])[A-Za-z\d!@#$%^&]{8,}$/;
    if (passwordRegex.test(password)) {
      isPasswordValid = true;
    } else {
      passwordError.textContent = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
      passwordError.style.display = 'block';
    }
  
    // Validate Confirm Password
    if (password === confirmPassword) {
      isConfirmPasswordValid = true;
    } else {
      confirmPasswordError.textContent = 'Passwords do not match.';
      confirmPasswordError.style.display = 'block';
    }
  
    // Validate Account Type
    if (accountType !== '--Select Account--') {
      isAccountTypeValid = true;
    } else {
      accountTypeError.textContent = 'Please select an account type.';
      accountTypeError.style.display = 'block';
    }
  
    // Display success or error message
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
  
    if (isNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid && isAccountTypeValid) {
      // Clear the form
      document.getElementById('contactForm').reset();
  
      // Display success message in a dialog box
     // alert(You, ${name}, have successfully submitted the form!);
  
      // Display success message on the page
      errorMessage.style.display = 'none';
      successMessage.style.display = 'block';
      successMessage.textContent = 'Form submitted successfully!';
    } else {
      // Display error message
      successMessage.style.display = 'none';
      errorMessage.style.display = 'block';
      errorMessage.textContent = 'Please fix the errors in the form.';
    }
  });