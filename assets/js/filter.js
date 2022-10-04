import {debounce} from 'lodash';
/**
 * Class for the filter post bundle
 * 
 * @property {HTMLElement} pagination   -- the element with the button(s) for pagination
 * @property {HTMLElement} content      -- the element with the main content
 * @property {HTMLFormElement} form     -- the form for the search
 * @property {HTMLElement} count        -- the element with number of posts on the content
 * @property {number} page              -- the number of page search
 */

export default class Filter {
   
    /**
     * Constructor the Filter class
     * 
     * @param {HTMLElement} section -- Element parant
     * 
     */
    constructor (section) {
        if (section == null){
            return;
        }

        this.pagination = section.querySelector('.js-filter-pagination');
        this.content = section.querySelector('.js-filter-content');
        this.count = section.querySelector('.js-filter-count');
        this.form = section.querySelector('.js-filter-form');
        this.page= parseInt(new URLSearchParams(window.location.search).get('page') || 1);
        this.moreNav = this.page == 1;
        this.bindEvents();
        this.reload = false;


        /**
         * Add the action to the elements of the filter bundle
         */
    }



    /**
     * add actions to the elements of the filter bundle
     */
    bindEvents() {

        const linkClickListener = (e) => {
            if(e.target.tagName === 'A' ) {
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'));
            }
        }

        if(this.moreNav){
      
            this.pagination.innerHTML = '<button id="refreshScroll" class="btn btn-primary btn-show-more mt-2">Voir plus</button>';
            this.pagination.querySelector('button').addEventListener('click', this.loadMore.bind(this));
            window.addEventListener('scroll', this.reload.bind(this));
            
        }else {
            this.pagination.addEventListener('click', linkClickListener);
        };

        this.form.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', debounce(this.loadForm.bind(this), 500));
        });

        this.form.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener('keyup', debounce(this.loadForm.bind(),500));
        });

    }



    /**
     * Load thee url in ajax
     * 
     * @param {URL} url -- url to load
     */
    async loadUrl(url, append = false){
        let stopReload =false
        this.showLoader();
        const params = new URLSearchParams(url.split('?')[1] || '');
        params.set('ajax', 1);

        const response = await fetch(`${url.split('?')[0]}?${params.toString()}`, {
            headers : {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if(response.status >= 200 && response.status < 300) {
            
            const data = await response.json();

            this.count.innerHTML = data.count;

            if(append){
                this.content.innerHTML += data.content;
            }else{
                this.content.innerHTML = data.content;
            }

            if(!this.moreNav){
                this.pagination.innerHTML = data.pagination;

            }else if(this.page === data.pages){
                console.log('none')
                stopReload =true
                this.pagination.style.display = 'none';
            }else{
                this.pagination.style.display = null;
                console.log('null')

            }

            if(data.pages==0){
                this.pagination.style.display = 'none';

            }

            params.delete('ajax');
            history.replaceState({},'', `${url.split('?')[0]}?${params.toString()}`);

        }else{
            console.log(response);
        }
        this.hideLoader(stopReload);
        
    }

    /**
     * Get the form and send request ajax with informations
     * 
     */
    async loadForm() {
        this.page = 1;
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') ||window.location.href );
        const params = new URLSearchParams();

        data.forEach((value, key) => {
            params.append(key, value);
        });

        return this.loadUrl(`${url.pathname}?${params.toString()}`);
    }

    /**
     *  Load more content on the page in scroll
     * 
     */
    async reload()
    {
        
        var rect = refreshScroll.getBoundingClientRect(), 
        offset = rect.bottom - window.innerHeight;

        if(offset < 40 && this.reload == false){
            this.loadMore(refreshScroll)
            this.reload = true
        }
    }

    /**
     *  Load more content on the page
     * 
     * @param {HTMLElement} button -- button show more 
     */
    async loadMore(button){
        if(button.target){
            button = button.target
        }
        console.log(this.page)
        button.setAttribute('disabled','disabled');
        this.page++;
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        params.set('page', this.page);
        console.log(this.page)

        await this.loadUrl(`${url.pathname}?${params.toString()}`, true);
        button.removeAttribute('disabled');
    }

    /**
     * Show the loader icon and disabled form wait response
     */
    showLoader(){
        const loader = this.form.querySelector('.js-loading');
        if(loader === null){
            return;
        }

        this.form.classList.add('is-loading');
        loader.setAttribute('aria-hidden', 'false');
        loader.style.display = null;
    }

    /**
     * Hide the loader icon and abled form after response
     */
     hideLoader(stopReload){
        const loader = this.form.querySelector('.js-loading');
        if(loader === null){
            return;
        }

        this.form.classList.remove('is-loading');
        loader.setAttribute('aria-hidden', 'true');
        loader.style.display = 'none';
        if(!stopReload){
            this.reload = false
        }


    }

}