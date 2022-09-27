import axios from 'axios';

const followSubject = document.querySelectorAll('[data-follow-subject]');
if(followSubject){
    followSubject.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            axios.get(`/subject/follow/${id}`);
            if(element.children[0].classList.contains("far")){
                element.children[0].classList.replace('far','fa' );
            }else{
                element.children[0].classList.replace('fa','far' );
            }
            
        });
    }))
}

const signalerSubject = document.querySelectorAll('[data-signaler-subject]');

if(signalerSubject){
    signalerSubject.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let div = document.getElementById('suggestSubject'+id);
            console.log(div);
            if(div.style.display != 'block') {
                div.style.display = 'block';
            }else{
                div.style.display = 'none';
            }
            
        });
    }))
}

const submitSuggestSubject = document.querySelectorAll('[data-submit-signaler-subject]');
if(submitSuggestSubject){
    submitSuggestSubject.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let message = document.getElementById('suggestSubject'+id).childNodes[1].value;
            console.log(message);
            axios.get(`/subject/signaler/${id}/${message}`)
            .then(function (reponse) {
                document.getElementById('suggestSubject'+id).style.display ="none"
                document.getElementById('subject'+id).style.display ="none"
            });
        });
    }))
}


const noter = document.querySelectorAll('[data-note-subject]');

if(noter){
    noter.forEach((element=>{
        element.addEventListener('click', () =>  {
            let value = element.value.split('-');
            let note = value[0];
            let subjectId = value[1];
            console.log(element.parentElement.childNodes , '😴');
            axios.get(`/subject/note/${note}/${subjectId}`);
            
            window.setTimeout(function(){
                element.parentNode.style.display = "none";
                },700);   

            let btn= element.parentElement.childNodes;
            btn.forEach(elements => {
                if(elements.nodeType == 1 ){
                    elements.style.display = "none";
                }
            });
            let newDiv = document.createElement("div");
            // et lui donne un peu de contenu
            let newContent = document.createTextNode('Note envoyée ✅');
            newDiv.appendChild(newContent);
            element.parentNode.insertBefore(newDiv, element);

        });
    }))
}

const subjectName = document.querySelectorAll('[data-edit-subjectName]');

if(subjectName){
    subjectName.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let name= document.getElementById('subjectName').value;
            console.log(id, name)
            axios.get(`/subject/edit/name/${id}/${name}`);
        });
    }))
}
const subjectContent = document.querySelectorAll('[data-edit-subjectContent]');

if(subjectContent){
    subjectContent.forEach((element=>{
        element.addEventListener('click', () =>  {
            let id = element.value;
            let content= document.getElementById('subjectContent').value;
            console.log(id, content)
            axios.get(`/subject/edit/content/${id}/${content}`);
        });
    }))
}


