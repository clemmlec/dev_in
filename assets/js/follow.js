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
   
    const followArticle = document.querySelectorAll('[data-follow-article]');
    if(followArticle){
        followArticle.forEach((element=>{
            element.addEventListener('click', () =>  {
                let id = element.value;
                axios.get(`/article/follow/${id}`);
                // console.log(element.children[0].contains("fa")); 
                if(element.children[0].classList.contains("far")){
                    element.children[0].classList.replace('far','fa' );
                }else{
                    element.children[0].classList.replace('fa','far' );
                }
                
            });
        }))
    }

    const signalerArticle = document.querySelectorAll('[data-signaler-article]');
    
    if(signalerArticle){
        signalerArticle.forEach((element=>{
            element.addEventListener('click', () =>  {
                let id = element.value;
                axios.get(`/article/signaler/${id}`)
                .then(function (reponse) {
                    console.log(reponse.data)
                    console.log(element.parentNode.parentNode)
                    element.parentNode.parentNode.style.display ="none"

                });
            });
        }))
    }

    const noter = document.querySelectorAll('[data-note-article]');
    
    if(noter){
        noter.forEach((element=>{
            // element.addEventListener('mouseover', () =>  {
            //     console.log(element.value);
            // });
            element.addEventListener('click', () =>  {
                let value = element.value.split('-');
                let note = value[0];
                let articleId = value[1];
                console.log(element.parentElement.childNodes , '😴');
                axios.get(`/article/note/${note}/${articleId}`);
                
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

    const articles = document.querySelectorAll('[data-switch-active-article]');
    
    if(articles){
        articles.forEach((element=>{
            element.addEventListener('change', () =>  {
                let id = element.value;
                axios.get(`/admin/article/switch/${id}`);
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
    
    const articleName = document.querySelectorAll('[data-edit-articleName]');
    
    if(articleName){
        articleName.forEach((element=>{
            element.addEventListener('click', () =>  {
                let id = element.value;
                let name= document.getElementById('articleName').value;
                console.log(id, name)
                axios.get(`/article/edit/name/${id}/${name}`);
            });
        }))
    }
    const articleContent = document.querySelectorAll('[data-edit-articleContent]');
    
    if(articleContent){
        articleContent.forEach((element=>{
            element.addEventListener('click', () =>  {
                let id = element.value;
                let content= document.getElementById('articleContent').value;
                console.log(id, content)
                axios.get(`/article/edit/content/${id}/${content}`);
            });
        }))
    }
    
    const newComment = document.querySelectorAll('[data-envoie-comment]');
    if(newComment){
        newComment.forEach((element=>{
            element.addEventListener('click', () =>  {
                // const form = document.getElementById('formpost');
                // const image = document.getElementById('article_imageFile_file');
                let articleId=element.value.split('|')[0];
                let userId=element.value.split('|')[1];
                let com= document.getElementById('commentPost'+articleId).value;
                let mesDonnees = new FormData();
                mesDonnees.set("article", articleId);
                mesDonnees.set("com", com);

                console.log(articleId, com);
                axios.post(
                    '/comment/new', mesDonnees )
                .then(function (reponse) {
                    //On traite la suite une fois la réponse obtenue 
                    // console.log(reponse, "reponse 😎");
                    // console.log(reponse.data);
                    console.log(articleId);
                    let comment = document.getElementById("modele-comment");
                    let top =  document.getElementById("com"+articleId);
                    let clone = comment.cloneNode(true);
                    console.log(clone.children[0]);

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

    const commentsSignaler = document.querySelectorAll('[data-signaler-comment]');
    
    if(commentsSignaler){
        commentsSignaler.forEach((element=>{
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