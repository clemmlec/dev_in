
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.visibility=='active'){
            content.style.maxHeight = null;
            content.style.visibility='hidden';
            content.style.marginBottom='0';
            content.style.paddingBottom='0';
            
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            content.style.visibility='active';
            content.style.marginBottom='25px';
            content.style.paddingBottom='25px';
        
        } 
    });
}
