let getSearchBtn = document.getElementById('searchBtn');

let getSearchForm = document.getElementById('searchForm');

console.log('test')

let getResultContainer = document.querySelector('.listOfMeetings');

getSearchBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    let formData = new FormData(getSearchForm);

    fetch('/rendez-vous/recherche', {
        method : 'POST',
        body : formData,
        }).then((response) => {return response.json()}
        ).then( data => {getResultContainer.innerHTML = data.content;}
        ).catch(e => console.log(e));
})