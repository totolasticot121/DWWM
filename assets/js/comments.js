
// CRUD forum comments


// Get topic comments
window.addEventListener('load', (event) => {
    getComments();
});

function getComments(){
    let commentsBox = document.querySelector('#comments-box'),
        id = document.querySelector('#topic-id').getAttribute('class'),
        url = '/forum/comment/' + id;
        
    fetch(url,{
        method: 'post'
    })
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        // Hide loader logo
        let loader = document.querySelector('.loader');
        if(loader){
            loader.parentNode.removeChild(loader)
        }
        // Insert new template into comments div
        commentsBox.innerHTML = data.template;
        // Animation on last comment
        let last = commentsBox.lastElementChild;
        last.classList.add("lastComment");
        // highlight syntax
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightBlock(block);
        });
        // delete comment on click
        deleteComment();
    })
    .catch(function(error) {
        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
    });   
}


// Add comment
document.querySelector('#new-comment-btn').addEventListener("click", function(e){
    
    e.preventDefault();

    let data = {
        content: document.querySelector('#comment-content').value,
        article_id: document.querySelector('#topic-id').getAttribute('class')
    }
    // Clear input after click
    document.querySelector('#comment-content').value = '';

    fetch('/forum/comment/new', {
        method: 'post',
        body: JSON.stringify(data)
    })
    .then((response) => {
        getComments();
    })
    .catch(function(error) {
        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
    });
});


// Delete comment
function deleteComment(){
    document.querySelectorAll('#delete-btn').forEach(element => {

        element.addEventListener("click", function(event){
            event.preventDefault();

            let comment_id = event.target.getAttribute('href'),
                token = event.target.getAttribute('value'),
                url = '/forum/comment/delete/' + comment_id,
                data = { token: token };
                
            fetch(url, {
                method: 'post',
                body: JSON.stringify(data)
            })
            .then((response) => {
                getComments();
            })
            .catch(function(error) {
                console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
            });
        });
    });
}