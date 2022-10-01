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
        axios.get(`/article/liked/${id}`);
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

        let message = document.getElementById('suggest'+id).childNodes[1].value;
        // console.log(message);
        axios.get(`/article/suggest/${id}/${message}`)
            .then(function (reponse) {
                document.getElementById('suggest'+id).style.display ="none"
        });
    }
   
}
