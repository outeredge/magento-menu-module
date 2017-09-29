import React from 'react';
import { DropTarget } from 'react-dnd';
import Target, { besideTarget } from './Target';
import Types from './Types';

@DropTarget(Types.ITEM, besideTarget, (connect, monitor) => ({
    connectDropTarget: connect.dropTarget(),
    isOverCurrent: monitor.isOver({ shallow: true }),
    canDrop: monitor.canDrop()
}))
export default class DragAndDropBeside extends Target {
    render() {
        const { connectDropTarget } = this.props;
        return connectDropTarget(
            <div className={ `drop-target-beside${this.state.isOver ? ' drop-target-beside-hover' : ''}` }></div>
        )
    }
};
