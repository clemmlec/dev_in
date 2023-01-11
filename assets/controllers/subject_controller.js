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
    follows(event) {
        let elem = event.target;
        if (event.target.type == "button" ){
            elem = event.target.childNodes[1]
        }
        let button = elem.parentNode
        let id = button.value;
        axios.get(`/subject/follow/${id}`)
        .catch(function (erreur) {
            if(erreur.response.data == "authentification requise"){
                window.location.href= "https://127.0.0.1:8000/login";
            }
            console.log(erreur.response.data , '👼');
        });
        if(elem.classList.contains("far")){
            elem.classList.replace('far','fa' );
        }else{
            elem.classList.replace('fa','far' );
        }
    }
    report(event) {
        let elem = event.target;
        if (event.target.type == "button" ){
            elem = event.target.childNodes[1]
        }
        let button = elem.parentNode
        let id = button.value;
        let div = document.getElementById('suggestSubject'+id);
        if(div.style.display != 'block') {
            div.style.display = 'block';
        }else{
            div.style.display = 'none';
        }

    }
    submitReport(event) {
        let elem = event.target
        if (event.target.type != "button" ){
            elem = event.target.parentNode
        }

        let id = elem.value;
        console.log(id);

        let message = document.getElementById('suggestSubject'+id).childNodes[1].childNodes[1].value;
        console.log(message);
        axios.get(`/subject/signaler/${id}/${message}`)
        .then(function (reponse) {
            document.getElementById('suggestSubject'+id).style.display ="none"
            document.getElementById('subject'+id).style.display ="none"
        })
        .catch(function (erreur) {
            if(erreur.response.data == "authentification requise"){
                window.location.href= "https://127.0.0.1:8000/login";
            }
            console.log(erreur.response.data , '👼');
        });
    }

    noter(event){
        console.log(event)
        // pour uniformiser le click entre le boutton et l'icone étoile
        let elem = event.target
        if (elem.type != "button" ){
            elem = event.target.parentNode
        }

        // récuperation des  valeurs

        let value = elem.value.split('-');
        let note = value[0];
        let subjectId = value[1];
        let verifSubjectId = elem.parentNode.parentNode.parentNode.id
        verifSubjectId = verifSubjectId.split('t')[1];

        // détection de fraude

        if(verifSubjectId != subjectId || note > 5 || note < 0){
            window.setTimeout(function(){
                elem.parentNode.style.display = "none";
                },700);   
            let btn= elem.parentElement.childNodes;
            btn.forEach(elements => {
                if(elements.nodeType == 1 ){
                    elements.style.display = "none";
                }
            });
            let divFraude = document.createElement("div");
            let responseFraude = document.createTextNode('Fraude detectée ⛔');
            divFraude.appendChild(responseFraude);
            elem.parentNode.insertBefore(divFraude, elem);
            return ;
        }

        axios.get(`/subject/note/${note}/${subjectId}`)
        .then(function (reponse) {
            // enleve les étoiles 
            let btn= elem.parentElement.childNodes;
            btn.forEach(elements => {
                if(elements.nodeType == 1 ){
                    elements.style.display = "none";
                }
            });

            // fait disparaitre la div du message après 0.7s
            window.setTimeout(function(){
                elem.parentNode.style.display = "none";
                },700);   
            // crée une div pour aficher la reponse positive
            let newDiv = document.createElement("div");
            let newContent = document.createTextNode('Note envoyée ✅');
            newDiv.appendChild(newContent);
            elem.parentNode.insertBefore(newDiv, elem);
            })
        .catch(function (erreur) {
            if(erreur.response.data == "fraude suspecté ⛔"){
                window.setTimeout(function(){
                    elem.parentNode.style.display = "none";
                    },700);   
                let btn= elem.parentElement.childNodes;
                btn.forEach(elements => {
                    if(elements.nodeType == 1 ){
                        elements.style.display = "none";
                    }
                });
                let divFraude = document.createElement("div");
                let responseFraude = document.createTextNode('Fraude detectée ⛔');
                divFraude.appendChild(responseFraude);
                elem.parentNode.insertBefore(divFraude, elem);
                return ;
            }
            console.log(erreur.response);
        });

    }
    
}
