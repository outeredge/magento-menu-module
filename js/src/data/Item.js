import Immutable from 'immutable';

const Item = Immutable.Record({
    item_id: '',
    menu_id: '',
    parent_id: null,
    title: '',
    description: '',
    url: '',
    image: '',
    product_id: null,
    category_id: null,
    page_id: null,
    sort_order: 0,
    items: Immutable.Map()
});

export default Item;