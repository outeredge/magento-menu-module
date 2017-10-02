import React from 'react';
import { findDOMNode } from 'react-dom';
import ItemActions from './../../data/ItemActions';
import ItemStore from './../../data/ItemStore';
import Item from './../../data/Item';
import { api } from './../../helpers/api';

export const besideTarget = {
    canDrop: (props, monitor) => props.item.item_id !== monitor.getItem().item.item_id,

    drop(props, monitor, component) {
        if (monitor.didDrop()) {
            return;
        }

        const dropDOMNode = findDOMNode(component);
        if (monitor.getItem().dragDOMNode.contains(dropDOMNode)) {
            return;
        }

        const item = monitor.getItem().item;
        const movedItem = monitor.getItem().item.merge({
            parent_id: props.item.parent_id,
            sort_order: props.before ? props.item.sort_order : props.item.sort_order + 1
        });

        const state = ItemStore.getState();
        const stateRemovedItem = state.deleteIn(ItemStore.getPath(item));
        ItemStore.setPath(movedItem);
        const stateAddedItem = stateRemovedItem.setIn(ItemStore.getPath(movedItem), new Item(movedItem));
        const stateSorted = stateAddedItem.getIn(ItemStore.getPath(movedItem, true)).map(sibling => {
            if (sibling.item_id === movedItem.item_id) {
                return sibling;
            }
            if (props.before) {
                if (sibling.sort_order >= movedItem.sort_order) {
                    return sibling.set('sort_order', sibling.sort_order + 1);
                }
            } else {
                if (sibling.sort_order > movedItem.sort_order) {
                    return sibling.set('sort_order', sibling.sort_order + 1);
                }
            }
            return sibling;
        }).sort(ItemStore.sortItems);
        const newTree = stateAddedItem.setIn(ItemStore.getPath(movedItem, true), stateSorted);
        ItemActions.updateTree(newTree);

        const sortData = [];
        stateSorted.forEach(item => {
            sortData.push(JSON.stringify(item));
        });
        api.saveItems(sortData);
    }
};

export const itemTarget = {
    canDrop: (props, monitor) => props.item.item_id !== monitor.getItem().item.item_id,

    drop(props, monitor, component) {
        if (monitor.didDrop()) {
            return;
        }

        const dropDOMNode = findDOMNode(component);
        if (monitor.getItem().dragDOMNode.contains(dropDOMNode)) {
            return;
        }

        const item = monitor.getItem().item;
        const movedItem = monitor.getItem().item.set('parent_id', props.item.item_id);
        api.saveItem(movedItem).then(() => {
            ItemActions.addItem(movedItem);
            ItemActions.deleteItem(item);
        });
    }
};

export default class Target extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isOver: false
        };
    }

    componentWillReceiveProps(nextProps) {
        if (!this.props.canDrop) {
            return;
        }
        if (!this.props.isOverCurrent && nextProps.isOverCurrent) {
            this.setState({ isOver: true });
        }
        if (this.props.isOverCurrent && !nextProps.isOverCurrent) {
            this.setState({ isOver: false });
        }
    }
};