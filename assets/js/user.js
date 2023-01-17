import axios from 'axios';

const btnLien = document.querySelectorAll('.page');
if(btnLien){
    btnLien.forEach((element=>{
        element.addEventListener('click', (event) =>  {

            if( event.target.childNodes[1]){
                window.location.href = event.target.childNodes[1].href
            }else{
                window.location.href = event.target.childNodes[0].href
            }
        });
    }))
}

const btn = document.querySelectorAll('.btn-inscription');
if(btn){
    btn.forEach((element=>{
        element.addEventListener('click', (event) =>  {

            if( event.target.childNodes[1]){
                window.location.href = event.target.childNodes[1].href
            }else{
                window.location.href = event.target.childNodes[0].href
            }
        });
    }))
}

const styleUser = document.querySelectorAll('[data-style-user]');
if(styleUser){
    styleUser.forEach((element=>{
        element.addEventListener('click', () =>  {
            let style = element.value;
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

