const form =document.querySelector('#form');
const error = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.querySelector('#inputEmail').value;
    const password = document.querySelector('#inputPassword').value;
    if(email.length > 0 && password.length > 0){
        form.submit();
    }else{
        error.innerText = 'Informations invalides.';
    }
})