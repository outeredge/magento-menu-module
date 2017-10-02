import ItemActionTypes from './ItemActionTypes';
import ItemDispatcher from './ItemDispatcher';

const Actions = {
    addItem(item) {
        ItemDispatcher.dispatch({
            type: ItemActionTypes.ADD_ITEM,
            item
        });
    },

    updateItem(item) {
        ItemDispatcher.dispatch({
            type: ItemActionTypes.UPDATE_ITEM,
            item
        });
    },

    deleteItem(item) {
        ItemDispatcher.dispatch({
            type: ItemActionTypes.DELETE_ITEM,
            item
        });
    },

    updateTree(state) {
        ItemDispatcher.dispatch({
            type: ItemActionTypes.UPDATE_TREE,
            state
        });
    }
};

export default Actions;