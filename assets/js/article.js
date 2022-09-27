import axios from 'axios';

const likedArticle = document.querySelectorAll('[data-liked-article]');
if(likedArticle){
    likedArticle.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            axios.get(`/article/liked/${id}`);
            if(element.children[0].classList.contains("far")){
                element.children[0].classList.replace('far','fa' );
            }else{
                element.children[0].classList.replace('fa','far' );
            }
            
        });
    }))
}

const suggestArticle = document.querySelectorAll('[data-suggest-article]');

if(suggestArticle){
    suggestArticle.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let div = document.getElementById('suggest'+id);
            if(div.style.display != 'block') {
                div.style.display = 'block';
            }else{
                div.style.display = 'none';
            }
        });
    }))
}

const submitSuggestArticle = document.querySelectorAll('[data-submit-suggest-article]');
if(submitSuggestArticle){
    submitSuggestArticle.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let message = document.getElementById('suggest'+id).childNodes[1].value;
            console.log(message);
            axios.get(`/article/suggest/${id}/${message}`)
            .then(function (reponse) {
                document.getElementById('suggest'+id).style.display ="none"
            });
        });
    }))
}