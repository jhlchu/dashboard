// create an array of objects with the id, trigger element (eg. button), and the content element
const tabElements = [
    {
        id: 'tax_regions',
        triggerEl: document.querySelector('#profile-tab-example'),
        targetEl: document.querySelector('#profile-example')
    },
    {
        id: 'tax_rates',
        triggerEl: document.querySelector('#dashboard-tab-example'),
        targetEl: document.querySelector('#dashboard-example')
    },
    {
        id: 'companies',
        triggerEl: document.querySelector('#settings-tab-example'),
        targetEl: document.querySelector('#settings-example')
    },
    {
        id: 'contacts',
        triggerEl: document.querySelector('#contacts-tab-example'),
        targetEl: document.querySelector('#contacts-example')
    }
];

// options with default values
const options = {
    defaultTabId: 'tax_regions',
    activeClasses: 'text-blue-600 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-400 border-blue-600 dark:border-blue-500',
    inactiveClasses: 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300',
    onShow: () => {
        //console.log('tab is shown');
    }
};

/*
* tabElements: array of tab objects
* options: optional
*/
const tabs = new Tabs(tabElements, options);