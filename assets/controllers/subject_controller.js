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
        axios.get(`/subject/follow/${id}`);
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
        });
    }

    noter(event){
        let elem = event.target
        // console.log(elem)
        if (elem.type != "button" ){
            console.log("merde")
            elem = event.target.parentNode
        }
        // console.log(elem)
        let value = elem.value.split('-');
        let note = value[0];
        let subjectId = value[1];
        // console.log(elem.parentElement.childNodes , '😴');
        axios.get(`/subject/note/${note}/${subjectId}`);
        
        window.setTimeout(function(){
            elem.parentNode.style.display = "none";
            },700);   

        let btn= elem.parentElement.childNodes;
        btn.forEach(elements => {
            if(elements.nodeType == 1 ){
                elements.style.display = "none";
            }
        });
        let newDiv = document.createElement("div");
        // et lui donne un peu de contenu
        let newContent = document.createTextNode('Note envoyée ✅');
        newDiv.appendChild(newContent);
        elem.parentNode.insertBefore(newDiv, elem);

    }
    
}
