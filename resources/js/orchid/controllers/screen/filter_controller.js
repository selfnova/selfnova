import {Controller} from 'stimulus';
import qs from 'qs';

export default class extends Controller {
    static get targets() {
        return ['filterItem'];
    }

    /**
     *
     * @param event
     */
    submit(event) {
        const screenEventSubmit = new Event('orchid:screen-submit');
        event.target.dispatchEvent(screenEventSubmit);

        this.setAllFilter();
        event.preventDefault();
    }

    onFilterClick(event) {
        const currentIndex = this.filterItemTargets.findIndex(target => target.classList.contains('show'));
        const elem = event.currentTarget;
        const index = parseInt(elem.dataset.filterIndex);
        const filterItem = this.filterItemTargets[index];

        if (currentIndex !== -1) {
            // hidden current filter item
            this.filterItemTargets[currentIndex].classList.remove('show');

            if (currentIndex === index) {
                return false;
            }
        }

        // show and position
        filterItem.classList.add('show');
        filterItem.style.top = `${elem.offsetTop}px`;
        filterItem.style.left = `${elem.offsetParent.offsetWidth - 4}px`;
        return false;
    }

    onMenuClick(event) {
        event.stopPropagation();
    }

    /**
     *
     */
    setAllFilter() {
        const formElement = document.getElementById('filters');

        const filters = window.platform.formToObject(formElement);
        filters.sort = this.getUrlParameter('sort');

        const params = qs.stringify(this.removeEmpty(filters), { encode: false })

        const url = `${window.location.origin + window.location.pathname}?${params}`;

        window.Turbolinks.visit(url, {action: 'replace'});
    }

    /**
     *
     * @param filter
     * @returns {*}
     */
    removeEmpty(filter) {
        Object.keys(filter).forEach((key) => {

            let value = filter[key];

            if(value === null || value === undefined  || value === ''){
                delete filter[key]
            }
        });

        return filter;
    }

    /**
     *
     * @param event
     */
    clear(event) {

        const params = {
            sort: this.getUrlParameter('sort'),
        };
        const url = `${window.location.origin + window.location.pathname}?${params}`;

        window.Turbolinks.visit(url, {action: 'replace'});
        event.preventDefault();
    }

    /**
     *
     * @param event
     */
    clearFilter(event) {
        const {filter} = event.target.dataset;
        document.querySelector(`input[name='filter[${filter}]']`).value = '';

        this.element.remove();
        this.setAllFilter();
        event.preventDefault();
    }

    /**
     *
     * @param property
     * @returns {string}
     */
    getUrlParameter(property) {
        const name = property.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(window.location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
}
