const form = document.getElementById('form');
const displayError = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let messages = [];
    let error = false;

    const description = document.getElementById('profile_form_description').value;
    const picture = document.getElementById('profile_form_picture');
    const fixNumber = document.getElementById('profile_form_fixNumber').value;
    const cellNumber = document.getElementById('profile_form_cellNumber').value;
    
    if(description.length < 15 ){
        messages.push('Description trop courte');
        error = true;
    }

    if(description.length > 3000 ){
        messages.push('Description trop longue');
        error = true;
    }

    if(validatePicture(picture)){
        messages.push('Taille du fichier chargé supérieur à 1024K');
        error = true;
    }

    if(fixNumber.length < 4 ){
        messages.push('Numéro de téléphone fixe trop court');
        error = true;
    }

    if(fixNumber.length > 20 ){
        messages.push('Numéro de téléphone fixe trop long');
        error = true;
    }

    if(cellNumber.length < 4 ){
        messages.push('Numéro de cellulaire trop court');
        error = true;
    }

    if(cellNumber.length > 20 ){
        messages.push('Numéro de cellulaire trop long');
        error = true;
    }

    if(error){
        displayError.innerText = messages.join(', ') + '.';
    }else{
        form.submit();
    }
})

function validatePicture(picture) {
    if (picture.files.length > 0) {
        for (const i = 0; i <= picture.files.length - 1; i++) {

            const fsize = picture.files.item(i).size;
            if (fsize >= 1024000) {
                return true;
            } else {
                return false;
            }
        }
    }
}