const form = document.querySelector('#form');
const displayError = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let messages = [];
    let error = false;

    const subject = document.querySelector('#contact_subject').value;
    const message = document.querySelector('#contact_message').value;

    if(subject.length < 5){
        messages.push('Sujet du message trop court');
        error = true;
    }

    if(subject.length > 100 ){
        messages.push('Sujet du message trop long');
        error = true;
    }

    if(message.length < 10 ){
        messages.push('Message trop court');
        error = true;
    }


    if( document.querySelector("#contact_firstName") ){
        const firstName = document.querySelector("#contact_firstName").value;
        const lastName = document.querySelector("#contact_lastName").value;
        const email = document.querySelector("#contact_email").value;
        const phoneNumber = document.querySelector("#contact_phone").value;

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

        if(!validateEmail(email)){
            messages.push('Email non valide');
            error = true;
        }

        if(phoneNumber.length < 4 ){
            messages.push('Numéro de téléphone trop court');
            error = true;
        }
    
        if(phoneNumber.length > 20 ){
            messages.push('Numéro de téléphone trop long');
            error = true;
        }

    }

    if(error){
        displayError.innerText = messages.join(', ') + '.';
    }else{
        form.submit();
    }

});

function validateEmail(email) {
    const mailformat = /\S+@\S+\.\S+/; 
    if(mailformat.test(email)){
        return true;
    }
    return false;
}