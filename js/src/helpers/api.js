class Api {
    constructor() {
        localStorage.removeItem('menu-products');
        localStorage.removeItem('menu-categories');
        localStorage.removeItem('menu-pages');

        this.getProducts = this.getProducts.bind(this);
        this.getCategories = this.getCategories.bind(this);
        this.getPages = this.getPages.bind(this);
        this.getUseLayoutGroup = this.getUseLayoutGroup.bind(this);
    }

    get headers() {
        return {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + this.token
        };
    }

    getItems(menuId, parentId) {
        return fetch(`/rest/V1/menuItem
?searchCriteria[filter_groups][0][filters][0][field]=menu_id
&searchCriteria[filter_groups][0][filters][0][value]=${menuId}
&searchCriteria[filter_groups][0][filters][0][condition_type]=eq
&searchCriteria[filter_groups][0][filters][1][field]=parent_id
&searchCriteria[filter_groups][0][filters][1][value]=${parentId ? parentId : 0}
&searchCriteria[filter_groups][0][filters][1][condition_type]=${parentId ? 'eq' : 'null'}`, {
            headers: this.headers
        }).then(res => res.json());
    }

    saveItem(item) {
        let method = 'POST';
        let url = '/rest/V1/menuItem';
        if (item.item_id) {
            method = 'PUT';
            url += '/' + item.item_id;
        }
        return fetch(url, {
            method: method,
            headers: this.headers,
            body: JSON.stringify({ item: item })
        }).then(res => res.json());
    }

    saveItems(items) {
        const url = '/rest/V1/menuItem/bulk';
        return fetch(url, {
            method: 'POST',
            headers: this.headers,
            body: JSON.stringify({ items: items })
        }).then(res => res.json());
    }

    deleteItem(itemId) {
        return fetch('/rest/V1/menuItem/' + itemId, {
            method: 'DELETE',
            headers: this.headers
        }).then(res => res.json());
    }

    getProducts(input, callback) {
        const productUrl = '/rest/V1/products?searchCriteria[page_size]=10';
        const data = localStorage.getItem('menu-products');
        if (!data) {
            fetch(productUrl, {
                    headers: this.headers
                })
                .then(res => res.json())
                .then(res => {
                    const products = res.items.map((item) => {
                        return { value: item.id, label: item.name + ' (' + item.id + ')' };
                    });
                    localStorage.setItem('menu-products', JSON.stringify(products));
                    callback(null, { options: products });
                });
        } else {
            callback(null, { options: JSON.parse(data) });
        }
    }

    getCategories(input, callback) {
        const categoryUrl = '/rest/all/V1/categories?searchCriteria[page_size]=10';
        const data = localStorage.getItem('menu-categories');
        if (!data) {
            fetch(categoryUrl, {
                    headers: this.headers
                })
                .then(res => res.json())
                .then(res => {
                    const categories = [];
                    function addChildrenToCategories(children) {
                        var keys = Object.keys(children);
                        if (keys.length) {
                            Object.keys(children).forEach(key => {
                                categories.push({
                                    value: children[key].id,
                                    label: children[key].name + ' (' + children[key].id + ')'
                                });
                                addChildrenToCategories(children[key].children_data);
                            });
                        }
                    }
                    addChildrenToCategories(res.children_data);
                    localStorage.setItem('menu-categories', JSON.stringify(categories));
                    callback(null, { options: categories });
                });
        } else {
            callback(null, { options: JSON.parse(data) });
        }
    }

    getPages(input, callback) {
        const pageUrl = '/rest/V1/cmsPage/search?searchCriteria[page_size]=30';
        const data = localStorage.getItem('menu-pages');

        if (!data) {
            fetch(pageUrl, {
                    headers: this.headers
                })
                .then(res => res.json())
                .then(res => {
                    const pages = res.items.map((item) => {
                        return { value: item.id, label: item.title + ' (' + item.id + ')' };
                    });
                    localStorage.setItem('menu-pages', JSON.stringify(pages));
                    callback(null, { options: pages });
                });
        } else {
            callback(null, { options: JSON.parse(data) });
        }
    }
    
    getUseLayoutGroup(input, callback) {
        const pageUrl = '/rest/V1/layoutGroup?searchCriteria[page_size]=10';
        const data = localStorage.getItem('menu-layout');

        if (!data) {
            fetch(pageUrl, {
                    headers: this.headers
                })
                .then(res => res.json())
                .then(res => {
                    const pages = res.items.map((item) => {
                        return { value: item.id, label: item.title + ' (' + item.id + ')' };
                    });
                    localStorage.setItem('menu-layout', JSON.stringify(pages));
                    callback(null, { options: pages });
                });
        } else {
            callback(null, { options: JSON.parse(data) });
        }
    }
}

export let api = new Api();