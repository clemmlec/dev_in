import axios from 'axios';

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


