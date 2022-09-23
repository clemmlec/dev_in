import axios from 'axios';

const newComment = document.querySelectorAll('[data-envoie-comment]');
if(newComment){
    newComment.forEach((element=>{
        element.addEventListener('click', () =>  {
            // const form = document.getElementById('formpost');
            // const image = document.getElementById('subject_imageFile_file');
            let subjectId=element.value.split('|')[0];
            let user=element.value.split('|')[1];
            let com= document.getElementById('commentPost'+subjectId).value;
            let mesDonnees = new FormData();
            mesDonnees.set("subject", subjectId);
            mesDonnees.set("com", com);

            console.log(subjectId, com);
            if( com != ''){
                axios.post(
                    '/comment/new', mesDonnees )
                .then(function (reponse) {
                    // On traite la suite une fois la réponse obtenue 
                    // creation et modification du clone
                    let comment = document.getElementById("modele-comment");
                    let top =  document.getElementById("com"+subjectId);
                    let clone = comment.cloneNode(true);
                    console.log(clone.children[0].children[0].children[1]);
                    clone.children[0].children[0].children[1].setAttribute('data-jaime-comment',"")
                    clone.classList.remove('modele');

                    clone.children[2].innerHTML=com;

                    top.insertBefore(clone, top.firstChild);
                })
                .catch(function (erreur) {
                    //On traite ici les erreurs éventuellement survenues
                    console.log(erreur);
                });
            }else{
                document.getElementById('commentPost'+subjectId).placeholder = "Votre commentaire est vide"
            }
        });
    }))
}

const jaime = document.querySelectorAll('[data-jaime-comment]');

if(jaime){
    jaime.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            axios.get(`/comment/jaime/${id}`)
            .then(function (reponse){
                let span = document.getElementById('like'+id);
                let nb =  span.textContent.split('(')[1].split(')')[0]

                if(reponse.data == 'commantaire liké'){
                    nb = +nb + 1;
                    span.textContent = '('+nb+')'
                }
                else if(reponse.data == 'commantaire déliker'){
                    nb = +nb - 1;
                    span.textContent = '('+nb+')'
                }
            });
        });
    }))
}

const commentsReport = document.querySelectorAll('[data-signaler-comment]');

if(commentsReport){
    commentsReport.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            axios.get(`/comment/signaler/${id}`)
            .then(function (reponse) {
                console.log(reponse.data)
                console.log(element.parentNode.parentNode.parentElement)
                element.parentNode.parentNode.parentElement.style.display ="none"
            });
        });
    }))
}