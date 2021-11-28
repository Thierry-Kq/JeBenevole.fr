document.addEventListener("DOMContentLoaded", function(event) {

    document.querySelectorAll('a.delete').forEach(function(link){
        link.addEventListener('click', onClickDelete);
    })
    document.querySelectorAll('a.anonymize').forEach(function(link){
        link.addEventListener('click', onClickAnonymize);
    })
});

function onClickAnonymize(event){
    event.preventDefault();
    if (confirm('Etes vous sûr?')) {
        const token = this.dataset.token;
        if (typeof token !== 'string' || token === '') {
            // do something
        } else {

            fetch(this.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({'_token': token})
            }).then(response => response.json()).then(json => { // invisibleName(json) => {} parenthese optionnelles si 1 seul params avec arrow function, json est le params

                if (json.code === 'success') {
                    // todo : change the html for the anonymised association
                    this.parentNode.remove();
                }
                displayMessage(json);

            });
        }
    }
}

function onClickDelete(event){
    event.preventDefault();
    if (confirm('Etes vous sûr?')) {
        const token = this.dataset.token;
        if (typeof token !== 'string' || token === '') {
            // do something
        } else {

            fetch(this.href, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({'_token': token})
            }).then(response => response.json()).then(json => { // invisibleName(json) => {} parenthese optionnelles si 1 seul params avec arrow function, json est le params

                if (json.code === 'success') {
                    // todo : remove the html for the deleted association
                }
                displayMessage(json);

            });
        }
    }
}

function displayMessage(json){
    const div = document.getElementById('message-ajax');

    div.classList.remove('hide', 'success', 'warning', 'error');
    div.classList.add(json.code);
    div.innerHTML = json.message;
}
