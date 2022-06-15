let getSearchBtn = document.getElementById('searchBtn');

let getSearchForm = document.getElementById('searchForm');

let getPagination = document.querySelectorAll('.pagination span a');

let ajaxRequired = false;


let getResultContainer = document.querySelector('.listOfMeetings');

getSearchBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    let formData = new FormData(getSearchForm);

    

    url = '/rendez-vous/recherche?'
    Array.from(formData).forEach(element => {
        url+= element[0] + '=' + element[1] + '&';
    });

    url+= 'page=1';

    fetch(url).then((response) => {return response.json()}
        ).then( data => {getResultContainer.innerHTML = data.content;}
        ).then(() => paginationListener()).catch(e => console.log(e));
})

const paginationListener = () => {
    getPagination = document.querySelectorAll('.pagination span a');
    getPagination.forEach(page => {
        page.addEventListener('click', (e) => {
                e.preventDefault();
                fetch(e.target.href).then((response) => {return response.json()}
                ).then( data => {getResultContainer.innerHTML = data.content;}
                ).then(()=> {paginationListener(); window.scroll({top : 0, behavior: 'smooth'})}).catch(e => console.log(e));
        })
    });
}