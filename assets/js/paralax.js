if(document.getElementsByClassName('paralax')[0]){
    window.addEventListener('scroll', function(e) {
    
        let s = this.scrollY;
        let w = this.outerWidth;
        let h = document.getElementsByClassName('paralax')[0].clientWidth;
        let p = s/h*100;

        document.getElementsByClassName('paralaxCard')[0].style=`transform: translate3d(0,-${p*10}px,0)`;

        document.getElementsByClassName('paralaxCard')[1].style=`transform: translate3d(0,-${p*12}px,0)`;

        document.getElementsByClassName('paralaxCard')[2].style=`transform: translate3d(0,-${p*7}px,0)`;

        document.getElementsByClassName('paralaxCard')[3].style=`transform: translate3d(0,-${p*8}px,0)`;

        document.getElementsByClassName('paralaxCard')[4].style=`transform: translate3d(0,-${p*12}px,0)`;

        document.getElementsByClassName('paralaxCard')[5].style=`transform: translate3d(0,-${p*13}px,0)`;

        document.getElementsByClassName('paralaxCard')[6].style=`transform: translate3d(0,-${p*14}px,0)`;

        document.getElementsByClassName('paralaxCard')[7].style=`transform: translate3d(0,-${p*15}px,0)`;

    })
}