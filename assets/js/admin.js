var myFilterBox = addFilterBox({
    target: {
        selector: 'div.pp-modules-list',
        items: 'div.pp-module',
    },
    addTo: {
        selector: 'div.pp-module-filter',
        position: 'append'
    },
    input: {
        label: 'Search: ',
        attrs: {
            placeholder: 'Search Modules'
        }
    },
    wrapper: {
        tag: 'div',
        attrs: {
            class: 'filterbox-wrap'
        }
    },
    displays: {
        counter: {
            tag: 'span',
            attrs: {
                class: 'counter'
            },
            addTo: {
                selector: '.filterbox-wrap',
                position: 'append'
            },
            text: function () {
                return '<strong>' + this.countVisible() + '</strong>/' + this.countTotal();
            }
        },
        noresults: {
            tag: 'div',
            addTo: {
                selector: 'div.pp-modules-list',
                position: 'after'
            },
            attrs: {
                class: 'no-results'
            },
            text: function () {
                return !this.countVisible() ? 'No matching widgets for "' + this.getFilter() + '".' : '';
            }
        }
    },
    filterAttr: 'data-filter',
    suffix: '-mysuffix',
    debuglevel: 2,
    inputDelay: 100,
    zebra: false,
    enableObserver: true,
    initTableColumns: true,
    useDomFilter: false
});