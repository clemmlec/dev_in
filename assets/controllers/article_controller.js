import { Controller } from '@hotwired/stimulus';
import axios from 'axios';

export default class extends Controller {
    connect() {
    }    
    follows(event) {
        
        let elem = event.target;
        
        if (event.target.type == "button" ){
            elem = event.target.childNodes[1]
        }
        if (event.target.localName == "span" ){
            elem = event.target.parentNode.childNodes[1]
        }

        if(elem.classList.contains("far")){
            elem.classList.replace('far','fa' );
            elem.parentNode.childNodes[3].childNodes[0].data  = +elem.parentNode.childNodes[3].childNodes[0].data +1
        }else{
            elem.classList.replace('fa','far' );
            elem.parentNode.childNodes[3].childNodes[0].data  = +elem.parentNode.childNodes[3].childNodes[0].data -1
        }
        // console.log(+elem.parentNode.childNodes[3].childNodes[0].data +1 );
        
        let button = elem.parentNode
        let id = button.value;

        axios.get(`/article/liked/${id}`)  
                  
        .then(function (reponse) {

        })
        .catch(function (erreur) {
            if(elem.classList.contains("far")){
                elem.classList.replace('far','fa' );
                elem.parentNode.childNodes[3].childNodes[0].data  = +elem.parentNode.childNodes[3].childNodes[0].data +1
            }else{
                elem.classList.replace('fa','far' );
                elem.parentNode.childNodes[3].childNodes[0].data  = +elem.parentNode.childNodes[3].childNodes[0].data -1
            }
            if(erreur.response.data == "authentification requise"){
                window.location.href= "https://127.0.0.1:8000/login";
            }
            console.log(erreur.response.data , '👼');
        });

    }
    report(event) {
        let elem = event.target;
        if (event.target.type == "button" ){
            elem = event.target.childNodes[1]
        }
        let button = elem.parentNode
        let id = button.value;
        let div = document.getElementById('suggest'+id);
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

        let message = document.getElementById('suggest'+id).childNodes[1].childNodes[1].value;
        axios.get(`/article/suggest/${id}/${message}`)
            .then(function (reponse) {
                document.getElementById('suggest'+id).style.display ="none"
        })
        .catch(function (erreur) {
            if(erreur.response.data == "authentification requise"){
                window.location.href= "https://127.0.0.1:8000/login";
            }
            console.log(erreur.response.data , '👼');
        });
    }
   
}
