import axios from 'axios';

const follow = document.querySelectorAll('[data-follow-user]');

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

const deleteUser = document.querySelectorAll('[data-delete-compte]');
if(deleteUser){
    deleteUser.forEach((element=>{
        element.addEventListener('click', () =>  {
            alertDelete.style.display = 'block';
        
        });
    }))
}

const retour = document.querySelectorAll('[data-retour]');
if(retour){
    retour.forEach((element=>{
        element.addEventListener('click', () =>  {
            alertDelete.style.display = 'none';
        });
    }))
}

