const form =document.getElementById('form');
const error = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('inputEmail').value;
    const password = document.getElementById('inputPassword').value;
    if(email.length > 0 && password.length > 0){
        form.submit();
    }else{
        error.innerText = 'Informations invalides.';
    }
})