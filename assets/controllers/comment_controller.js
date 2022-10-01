import { Controller } from '@hotwired/stimulus';
import axios from 'axios';
/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
    }    

    newComment(event) {
        let element = event.target;
        if (event.target.type != "button" ){
            element = event.target.parentNode
        }
        // console.log(element)
        let subjectId=element.value.split('|')[0];
        let user=element.value.split('|')[1];
        let com= document.getElementById('commentPost'+subjectId).value;
        let mesDonnees = new FormData();
        mesDonnees.set("subject", subjectId);
        mesDonnees.set("com", com);
        // console.log(subjectId, com , '🦸‍♀️🦸‍♂️🦹‍♂️🦹‍♀️');
        if( com != ''){
            axios.post(
                '/comment/new', mesDonnees )
            .then(function (reponse) {
                // On traite la suite une fois la réponse obtenue 
                // creation et modification du clone
                let comment = document.getElementById("modele-comment");
                let top =  document.getElementById("com"+subjectId);
                let clone = comment.cloneNode(true);
                // console.log(clone);
                clone.children[0].children[0].children[1].value = reponse.data;
                clone.children[0].children[0].children[0].value = reponse.data;
                clone.children[0].children[0].children[1].children[1].setAttribute('id', 'like'+reponse.data)
                clone.children[0].children[1].setAttribute('id', 'suggest'+reponse.data)
                clone.setAttribute('id', 'comment'+reponse.data)
                clone.children[0].children[1].children[1].value= reponse.data;
                clone.classList.remove('modele');

                clone.children[2].innerHTML=com;

                top.insertBefore(clone, top.firstChild);
                // console.log(reponse.data , '🎅🤶')
            })
            .catch(function (erreur) {
                //On traite ici les erreurs éventuellement survenues
                document.getElementById('commentPost'+subjectId).value="";
                document.getElementById('commentPost'+subjectId).placeholder = erreur.response.data;
                console.log(erreur.response.data , '👼');
            });
        }else{
            document.getElementById('commentPost'+subjectId).placeholder = "Votre commentaire est vide"
        }
    }

    follows(event) {
        let element = event.target;
        if (event.target.type != "button" ){
            element = event.target.parentNode
        }
        // console.log(element)
        let id = element.value;
        // console.log(id)
        axios.get(`/comment/jaime/${id}`)
            .then(function (reponse){
                let span = document.getElementById('like'+id);
                let nb =  span.textContent.split('(')[1].split(')')[0]
                console.log(element.childNodes)
                if(reponse.data == 'commantaire liké'){
                    nb = +nb + 1;
                    span.textContent = '('+nb+')'
                    element.childNodes[1].classList.replace('far','fa-solid' );
                }
                else if(reponse.data == 'commantaire déliker'){
                    nb = +nb - 1;
                    span.textContent = '('+nb+')'
                    element.childNodes[1].classList.replace('fa-solid','far' );
                }
            });
    }
    report(event) {
        let element = event.target;
        if (event.target.type != "button" ){
            element = event.target.parentNode
        }
            let id = element.value;
            let div = document.getElementById('suggest'+id);
            // console.log(div);
            if(div.style.display != 'block') {
                div.style.display = 'block';
            }else{
                div.style.display = 'none';
            }
    }
    submitReport(event) {
        let element = event.target
        if (event.target.type != "button" ){
            element = event.target.parentNode
        }
        let id = element.value;
        let message = document.getElementById('suggest'+id).childNodes[1].childNodes[1].value;
        // console.log(document.getElementById('suggest'+id).children);
        console.log(message);
        axios.get(`/comment/signaler/${id}/${message}`)
        .then(function (reponse) {
            document.getElementById('suggest'+id).style.display ="none"
            document.getElementById('comment'+id).style.display ="none"
        });
    }
   
}
