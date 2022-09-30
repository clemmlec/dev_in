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
        axios.get(`/user/follow/${id}`);
        if(button.children[0].classList.contains("far")){
            button.children[0].classList.replace('far','fa' );
        }else{
            button.children[0].classList.replace('fa','far' );
        }
    }
   
}
