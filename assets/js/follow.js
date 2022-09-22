console.log('axios follow valide 😅')
import axios from 'axios';



window.onload = () => {
    const follow = document.querySelectorAll('[data-follow-user]');
    
    if(follow){
        follow.forEach((element=>{
            element.addEventListener('click', () =>  {
               
                let id = element.value;
                axios.get(`/user/follow/${id}`);
                if(element.children[0].classList.contains("far")){
                    element.children[0].classList.replace('far','fa' );
                }else{
                    element.children[0].classList.replace('fa','far' );
                }
            });
        }))
    }
   
    const followSubject = document.querySelectorAll('[data-follow-subject]');
    if(followSubject){
        followSubject.forEach((element=>{
            element.addEventListener('click', () =>  {
                let id = element.value;
                axios.get(`/subject/follow/${id}`);
                // console.log(element.children[0].contains("fa")); 
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
                axios.get(`/subject/signaler/${id}`)
                .then(function (reponse) {
                    console.log(reponse.data)
                    console.log(element.parentNode.parentNode)
                    element.parentNode.parentNode.style.display ="none"

                });
            });
        }))
    }

    const noter = document.querySelectorAll('[data-note-subject]');
    
    if(noter){
        noter.forEach((element=>{
            // element.addEventListener('mouseover', () =>  {
            //     console.log(element.value);
            // });
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
                // element.parentNode.document.createElement("div");
                element.parentNode.insertBefore(newDiv, element);
                // element.parentNode.parentNode.removeChild(element.parentNode);
                // element.style('display', 'none');

            });
        }))
    }

    const subjects = document.querySelectorAll('[data-switch-active-subject]');
    
    if(subjects){
        subjects.forEach((element=>{
            element.addEventListener('change', () =>  {
                let id = element.value;
                axios.get(`/admin/subject/switch/${id}`);
            });
        }))
    }

    const users = document.querySelectorAll('[data-switch-active-user]');
    
    if(users){
        users.forEach((element=>{
            element.addEventListener('change', () =>  {
                let id = element.value;
                axios.get(`/admin/user/switch/${id}`);
            });
        }))
    }

    const comments = document.querySelectorAll('[data-switch-active-comment]');
    
    if(comments){
        comments.forEach((element=>{
            element.addEventListener('change', () =>  {
                let id = element.value;
                axios.get(`/admin/comment/switch/${id}`);
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
    
    const newComment = document.querySelectorAll('[data-envoie-comment]');
    if(newComment){
        newComment.forEach((element=>{
            element.addEventListener('click', () =>  {
                // const form = document.getElementById('formpost');
                // const image = document.getElementById('subject_imageFile_file');
                let subjectId=element.value.split('|')[0];
                let userId=element.value.split('|')[1];
                let com= document.getElementById('commentPost'+subjectId).value;
                let mesDonnees = new FormData();
                mesDonnees.set("subject", subjectId);
                mesDonnees.set("com", com);

                console.log(subjectId, com);
                axios.post(
                    '/comment/new', mesDonnees )
                .then(function (reponse) {
                    //On traite la suite une fois la réponse obtenue 
                    // console.log(reponse, "reponse 😎");
                    // console.log(reponse.data);
                    console.log(subjectId);
                    let comment = document.getElementById("modele-comment");
                    let top =  document.getElementById("com"+subjectId);
                    let clone = comment.cloneNode(true);
                    console.log(clone.children[0].children[0].children[1]);
                    clone.children[0].children[0].children[1].setAttribute('data-jaime-comment',"")
                    // button
                    //console.log(clone.children[0].childNodes[1].childNodes[3]);

                    clone.classList.remove('modele');



                    // et lui donne un peu de contenu
                    // let newContent = document.createTextNode(com);
                    clone.children[2].innerHTML=com;
                    // // element.parentNode.document.createElement("div");
                    top.insertBefore(clone, top.firstChild);
                    // comment.remove();

                })
                .catch(function (erreur) {
                    //On traite ici les erreurs éventuellement survenues
                    console.log(erreur);
                });
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

    const styleUser = document.querySelectorAll('[data-style-user]');
    if(styleUser){
        styleUser.forEach((element=>{
            element.addEventListener('click', () =>  {
                let style = element.value;
                console.log(style)
                axios.get(`/user/style/${style}`);
                element.innerHTML="✅";
           
                
            });
        }))
    }
}