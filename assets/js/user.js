import axios from 'axios';

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