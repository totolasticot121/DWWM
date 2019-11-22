let search = document.querySelector('#search-bar');
let display = document.querySelector('#display');


if(display){
    document.addEventListener('click', (e) => {
        if (e.target.id != ('display' && 'search')) {
            display.style.display = 'none';
        }
    });
}

search.addEventListener('keypress', (e) => {

    let json = {};
    json.content = e.target.value;

    fetch('/api/search', {
        method: 'post',
        body: JSON.stringify(json)
    }).then(function (response){

        return response.json().then((data) => {
            
            if(data.result)
            {
                let display = document.querySelector('#display');
                let res = '<div>';

                JSON.parse(data.results).forEach((e) => {
                    res += '<div class="search-results"><a href="/forum/' + e.id + '" class="search-results">' + e.title + '</a></div>';
                });

                res += '</div>';

                display.innerHTML = res;
                display.style.display = 'block';

            } else {

                let display = document.querySelector('#display');
                let res = '<div>';
                    res += '<p class="mt-3 ml-3">Aucun résultat</p>';
                    res += '</div>';
                display.innerHTML = res;
                display.style.display = 'block';
            }
        })
    }).catch(function (err) {
        console.log('Erreur: problème requetes');
    })
})

