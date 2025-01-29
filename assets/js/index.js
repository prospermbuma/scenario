/* =================================================
# Get Form Data
==================================================*/
const register_form = document.querySelector('.form-wrapper form'),
    fname = register_form.querySelector('#fname'),
    lname = register_form.querySelector('#lname'),
    email = register_form.querySelector('#email'),
    phone = register_form.querySelector('#phone'),
    pswd_1 = register_form.querySelector('#pswd1'),
    pswd_2 = register_form.querySelector('#pswd2');
const message = document.querySelector('.message');

register_form.onsubmit = (e) => {
    e.preventDefault();

    // Create new XMLHttpRequest Object
    const xhr = new XMLHttpRequest();

    // Open connection
    xhr.open('POST', 'src/register.php', true);
    // xhr.open('POST', '/test_cases/automated/UserRegistrationTest.php', true);

    xhr.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            let response = xhr.response;
            if (response.indexOf("Sorry, user already taken") != -1 || response.indexOf("Fields cannot be blank") != -1 || response.indexOf("Password mismatch") != -1 || response.indexOf("Not saved ") != -1 || response.indexOf("Invalid email address") != -1 || response.indexOf("Fatal error") != -1 || response.indexOf("Server error") != -1) {
                message.classList.remove('success');
                message.classList.add('error');
                message.innerText = response;
                setTimeout(() => {
                    message.innerText = "";
                    message.classList.remove('error');
                }, 3000);
            } else {
                /* === Show and hide success message  === */
                message.classList.remove('error');
                message.classList.add('success');
                message.innerText = response;
                setTimeout(() => {
                    message.innerText = "";
                    message.classList.remove('success');
                }, 3000);

                /* === Clear inputs  === */
                register_form.reset();
            }
        }
    }
    // Creating new formData object.
    let formData = new FormData(register_form);

    // Send the request (form data)
    xhr.send(formData);
}