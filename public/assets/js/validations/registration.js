const form = document.querySelector('#form');
const displayError = document.querySelector('.error');


form.addEventListener('submit', (e) => {
    e.preventDefault();
    let messages = [];
    let error = false;

    // Get form inputs
    const lastName = document.querySelector('#registration_form_lastName').value;
    const firstName = document.querySelector('#registration_form_firstName').value;
    const nickname = document.querySelector('#registration_form_nickname').value;
    const email = document.querySelector('#registration_form_email').value;
    const password = document.querySelector('#registration_form_plainPassword_first').value;
    const confirmation = document.querySelector('#registration_form_plainPassword_second').value;
    const rgpd = document.querySelector('#registration_form_rgpd').value;

;    //Check inputs
    if(lastName.length < 2){
        messages.push('Nom trop court');
        error = true;
    }

    if(lastName.length > 50){
        messages.push('Nom trop long');
        error = true;
    }

    if(firstName.length < 2){
        messages.push('Prénom trop court');
        error = true;
    }

    if(firstName.length > 50){
        messages.push('Prénom trop long');
        error = true;
    }

    if(nickname.length < 5){
        messages.push('Pseudo trop court');
        error = true;
    }

    if(nickname.length > 50){
        messages.push('Pseudo trop long');
        error = true;
    }

    if(!validateEmail(email)){
        messages.push('Email non valide');
        error = true;
    }

    if(!validatePassword(password)){
        messages.push('Le mot de passe doit contenir au moins 12 caractéres dont 1 majuscule, 1 minuscule, 1 nombre et 1 caractére spécial');
        error = true;
    }

    if(password.length > 50){
        messages.push('Mot de passe trop long');
        error = true;
    }

    if(password != confirmation){
        messages.push('Le mot de passe et sa confirmation différent');
        error = true;
    }

    if(rgpd.checked == false){
        messages.push('Vous devez accepter le Règlement Général sur la Protection des Données');
        error = true;
    }

    //Use error boolean to choose if data will be send or error return
    if(error){
        displayError.innerText = messages.join(', ') + '.';
    }else{
        form.submit();
    }

})

function validateEmail(email) {
    const mailformat = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; 
    if(mailformat.test(email)){
        return true;
    }
    return false;
}

function validatePassword(password) {
    const passwordFormat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/; 
    if(passwordFormat.test(password)){
        return true;
    }
    return false;
}