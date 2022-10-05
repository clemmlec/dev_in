window.addEventListener('scroll', function(e) {
    let s = this.scrollY;
    let w = this.outerWidth;
    let h = document.getElementsByClassName('paralax')[0].clientWidth;
    let p = s/h*100;

    document.getElementsByClassName('paralaxCard')[0].style=`transform: translate3d(0,-${p*10}px,0)`;

    document.getElementsByClassName('paralaxCard')[1].style=`transform: translate3d(0,-${p*18}px,0)`;

    document.getElementsByClassName('paralaxCard')[2].style=`transform: translate3d(0,-${p*25}px,0)`;

    document.getElementsByClassName('paralaxCard')[3].style=`transform: translate3d(0,-${p*15}px,0)`;

    document.getElementsByClassName('paralaxCard')[4].style=`transform: translate3d(0,-${p*15}px,0)`;

    document.getElementsByClassName('paralaxCard')[5].style=`transform: translate3d(0,-${p*23}px,0)`;

    document.getElementsByClassName('paralaxCard')[6].style=`transform: translate3d(0,-${p*30}px,0)`;

    document.getElementsByClassName('paralaxCard')[7].style=`transform: translate3d(0,-${p*27}px,0)`;
  
    document.getElementsByClassName('paralaxCard')[8].style=`transform: translate3d(0,-${p*32}px,0)`;

    document.getElementsByClassName('paralaxCard')[9].style=`transform:  translate3d(0,-${p*37}px,0)`;

    document.getElementsByClassName('backround')[0].style=`transform: translate(-50%,-50%) rotate(${p}deg)`;
    document.getElementsByClassName('backround')[1].style=`transform: translate(-50%,-50%) rotate(${p}deg)`;
    console.log(document.getElementsByClassName('backround')[0].style.rotate)


})