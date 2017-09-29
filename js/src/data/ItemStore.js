import Immutable from 'immutable';
import { ReduceStore } from 'flux/utils';
import ItemActionTypes from './ItemActionTypes';
import ItemDispatcher from './ItemDispatcher';
import Item from './Item';

class ItemStore extends ReduceStore {
    constructor() {
        super(ItemDispatcher);
        this.paths = {};
    }

    getInitialState() {
        return Immutable.Map();
    }

    setPath(item) {
        if (item.parent_id) {
            this.paths[item.item_id] = this.paths[item.parent_id] + '/' + item.item_id;
        } else {
            this.paths[item.item_id] = String(item.item_id);
        }
    }

    getPath(item, siblings) {
        const itemPath = [];
        if (item.parent_id) {
            this.paths[item.parent_id].split('/').forEach((res) => {
                itemPath.push(parseInt(res));
                itemPath.push('items');
            });
        }
        if (!siblings) {
            itemPath.push(item.item_id);
        }
        return itemPath;
    }

    sortItems(a, b) {
        if (a.sort_order < b.sort_order) { return -1; }
        if (a.sort_order > b.sort_order) { return 1; }
        return 0;
    }

    reduce(state, action) {
        switch (action.type) {
            case ItemActionTypes.ADD_ITEM:
                this.setPath(action.item);
                return state.setIn(this.getPath(action.item), new Item(action.item));

            case ItemActionTypes.UPDATE_ITEM:
                return state.setIn(this.getPath(action.item), new Item(action.item));

            case ItemActionTypes.DELETE_ITEM:
                return state.deleteIn(this.getPath(action.item));

            case ItemActionTypes.UPDATE_TREE:
                return action.state;

            default:
                return state;
        }
    }
}

export default new ItemStore();