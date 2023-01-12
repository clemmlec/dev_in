const dropbtn = document.querySelector('#dropbtn');

dropbtn.addEventListener('click', () =>  {
    const dropcontent = document.querySelector('#dropcontent');

    if(dropcontent.style.display == '' || dropcontent.style.display == 'none') {
        dropcontent.style.display = 'block';
    }else{
        dropcontent.style.display = 'none';
    }

});
