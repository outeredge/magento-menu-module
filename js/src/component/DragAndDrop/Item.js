import React from 'react';
import { findDOMNode } from 'react-dom';
import { DragSource, DropTarget } from 'react-dnd';
import Target, { itemTarget } from './Target';
import { saveItem } from './../../helpers/api';
import Item from './../Item';
import ItemActions from './../../data/ItemActions';
import Types from './Types';

const itemSource = {
    beginDrag(props, monitor, component) {
        return Object.assign({}, props, { dragDOMNode: findDOMNode(component) });
    }
};

@DropTarget(Types.ITEM, itemTarget, (connect, monitor) => ({
    connectDropTarget: connect.dropTarget(),
    isOverCurrent: monitor.isOver({ shallow: true }),
    canDrop: monitor.canDrop()
}))
@DragSource(Types.ITEM, itemSource, (connect, monitor) => ({
    connectDragSource: connect.dragSource()
}))
export default class DragAndDropItem extends Target {
    render() {
        const { connectDropTarget, connectDragSource, item } = this.props;
        return connectDropTarget(connectDragSource(
            <div className={this.state.isOver ? 'drop-target-item-hover' : ''}>
                <Item item={item} key={item.item_id} />
            </div>
        ))
    }
}