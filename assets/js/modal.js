function modal(bouton){

    id=bouton.id.split('n')[1];
    id='modal'+id
    var mod = document.getElementsByClassName("modal");
    
    var i;
    for (i = 0; i < mod.length; i++) {
        mod[i].style.display='none';
    }
    console.log(id)    // Get the <span> element that closes the modal

    var modal = document.getElementById(id);


    // When the user clicks the button, open the modal 
    modal.style.display = "block";
    console.log(modal)    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    var quit = document.getElementsByClassName("close")[1];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
    quit.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
        modal.style.display = "none";
        }
    }
}