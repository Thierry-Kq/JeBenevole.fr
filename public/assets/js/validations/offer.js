const form = document.querySelector('#form');
const displayError = document.querySelector('.error');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let messages = [];
    let error = false;

    const title = document.querySelector('#offer_title').value;
    const address = document.querySelector('#offer_address').value;
    const zip = document.querySelector('#offer_zip').value;
    const city = document.querySelector('#offer_city').value;
    const description = document.querySelector('#offer_description').value;
    const startdateD = document.querySelector('#offer_dateStart_day').value;
    const startdateM = document.querySelector('#offer_dateStart_month').value;
    const startdateY = document.querySelector('#offer_dateStart_year').value;
    const enddateD = document.querySelector('#offer_dateEnd_day').value;
    const enddateM = document.querySelector('#offer_dateEnd_month').value;
    const enddateY = document.querySelector('#offer_dateEnd_year').value;
    const picture = document.querySelector('#offer_file');
    const name = document.querySelector('#offer_contactExternalName').value;
    const email = document.querySelector('#offer_contactExternalEmail').value;
    const phone = document.querySelector('#offer_contactExternalTel').value;

    if(title.length < 30 ){
        messages.push('Titre de l\'offre trop court');
        error = true;
    }

    if(title.length > 255 ){
        messages.push('Titre de l\'offre trop long');
        error = true;
    }

    if(address.length < 15 ){
        messages.push('Adresse de l\'offre trop courte');
        error = true;
    }

    if(address.length > 255 ){
        messages.push('Adresse de l\'offre trop longue');
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

    if(name.length < 2 ){
        messages.push('Nom du contact externe trop court');
        error = true;
    }

    if(name.length > 100 ){
        messages.push('Nom du contact externe trop long');
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

    if(phone.length < 4 ){
        messages.push('Numéro du contact trop court');
        error = true;
    }

    if(phone.length > 20 ){
        messages.push('Numéro du contact trop long');
        error = true;
    }

    let today = new Date();
    let todaydate = today.getFullYear().toString()+('0'+parseInt(today.getMonth()+1)).slice(-2).toString()+('0'+today.getDate()).slice(-2).toString();
    let startdate = startdateY+('0'+startdateM).slice(-2)+('0'+startdateD).slice(-2);
    let enddate = enddateY+('0'+enddateM).slice(-2)+('0'+enddateD).slice(-2);

    if(startdate < todaydate){
        messages.push('La date du début de l\'offre doit être postérieur à la date du jour');
        error = true;
    }

    if(enddate < todaydate) {
        messages.push('La date du début de l\'offre doit être antérieur à la date de fin de l\'offre');
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
