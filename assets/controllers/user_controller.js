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
        let button = elem.parentNode
        
        let id = button.value;

        axios.get(`/user/follow/${id}`)
        .catch(function (erreur) {
            if(erreur.response.data == "authentification requise"){
                window.location.href= "https://127.0.0.1:8000/login";
            }

        }).then(function (reponse) {
            if(button.children[0].classList.contains("far")){
                button.children[0].classList.replace('far','fa' );
            }else{
                button.children[0].classList.replace('fa','far' );
            }
        });
        
    }
   
}
