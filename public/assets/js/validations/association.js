const form = document.querySelector('#form');
const displayError = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let messages = [];
    let error = false;

    const associationName = document.querySelector('#association_name').value;
    const email = document.querySelector('#association_email').value;
    const address = document.querySelector('#association_address').value;
    const zip = document.querySelector('#association_zip').value;
    const city = document.querySelector('#association_city').value;
    const fixNumber = document.querySelector('#association_fixNumber').value;
    const cellNumber = document.querySelector('#association_cellNumber').value;
    const faxNumber = document.querySelector('#association_faxNumber').value;
    const description = document.querySelector('#association_description').value;
    const picture = document.querySelector('#association_picture');
    const webSite = document.querySelector('#association_webSite').value;
    const facebook = document.querySelector('#association_facebook').value;
    const youtube = document.querySelector('#association_youtube').value;
    const linkedin = document.querySelector('#association_linkedin').value;
    const twitter = document.querySelector('#association_twitter').value;

    if(associationName.length < 2 ){
        messages.push('Nom de l\'association trop court');
        error = true;
    }

    if(associationName.length > 100 ){
        messages.push('Nom de l\'association trop long');
        error = true;
    }

    if(email.length <=0 || email === ""){
        messages.push('Vous devez entrer un email');
        error = true;
    }

    if(!validateEmail(email)){
        messages.push('Email non valide');
        error = true;
    }

    if(address.length < 2 ){
        messages.push('Adresse trop courte');
        error = true;
    }

    if(address.length > 100 ){
        messages.push('Adresse trop longue');
        error = true;
    }

    if(zip.length < 5 ){
        messages.push('Code postal trop court');
        error = true;
    }

    if(zip.length > 10 ){
        messages.push('Code postal trop long');
        error = true;
    }

    if(city.length < 2 ){
        messages.push('Nom de la ville trop court');
        error = true;
    }

    if(city.length > 100 ){
        messages.push('Nom de la ville trop long');
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

    if(faxNumber.length < 4 ){
        messages.push('Numéro de fax trop court');
        error = true;
    }

    if(faxNumber.length > 20 ){
        messages.push('Numéro de fax trop long');
        error = true;
    }

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

    if(!validateWebSite(webSite)){
        messages.push('Lien de votre site non valide');
        error = true;
    }

    if(!validateFacebook(facebook)){
        messages.push('Lien vers facebook non valide');
        error = true;
    }

    if(!validateYoutube(youtube)){
        messages.push('Lien vers youtube non valide');
        error = true;
    }

    if(!validateLinkedin(linkedin)){
        messages.push('Lien vers linkedIn non valide');
        error = true;
    }

    if(!validateTwitter(twitter)){
        messages.push('Lien vers twitter non valide');
        error = true;
    }

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

function validateWebSite(url) {
    const urlformat = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi; 
    if(urlformat.test(url)){
        return true;
    }
    return false;
}

function validateFacebook(url) {
    const urlformat = /^(https?:\/\/)?(www\.)?([A-Za-z]{2}-[A-Za-z]{2}.)?facebook\.com\/[a-zA-Z0-9_]*$/; 
    if(urlformat.test(url)){
        return true;
    }
    return false;
}

function validateYoutube(url) {
    const urlformat = /^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be|youtube-nocookie\.com))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/; 
    if(urlformat.test(url)){
        return true;
    }
    return false;
}

function validateLinkedin(url) {
    const urlformat = /^(https?:\/\/)(?:[\w]+\.)?linkedin\.com\/[a-zA-Z0-9_/]*$/; 
    if(urlformat.test(url)){
        return true;
    }
    return false;
}

function validateTwitter(url) {
    const urlformat = /^(https?:\/\/)?(www\.)?twitter\.com\/[a-zA-Z0-9_]*$/; 
    if(urlformat.test(url)){
        return true;
    }
    return false;
}