import React from 'react';
import PropTypes from 'prop-types';
import View from './View';
import Edit from './Edit';
import Create from './Create';
import DragAndDropItem from './DragAndDrop/Item';
import DragAndDropBeside from './DragAndDrop/Beside';
import { getItems, deleteItem } from './../helpers/api';
import ItemActions from './../data/ItemActions';

class Item extends React.PureComponent {

    constructor(props) {
        super(props);
        this.state = {
            childrenVisible: false
        };

        this.toggleChildren = this.toggleChildren.bind(this);
        this.fetchChildren = this.fetchChildren.bind(this);

        this.delete = this.delete.bind(this);
    }

    toggleChildren(event) {
        event.preventDefault();
        if (this.state.childrenVisible) {
            this.setState({ childrenVisible: false });
        } else {
            this.fetchChildren();
        }
    }

    fetchChildren() {
        getItems(this.context.menu_id, this.props.item.item_id).then(res => {
            res.items.forEach(item => {
                ItemActions.addItem(item);
            });
            this.setState({ childrenVisible: true });
        });
    }

    delete(event) {
        event.preventDefault();
        if (!window.confirm('Are you sure you want to delete this menu item?')) {
            return;
        }
        deleteItem(this.props.item.item_id).then(() => {
            ItemActions.deleteItem(this.props.item);
        });
    }

    render() {
        let items = [];
        if (this.state.childrenVisible) {
            [...this.props.item.items.values()].map(item => {
                items.push(<DragAndDropItem item={item} key={item.item_id} />);
            });
        }

        return (
            <div className="item">
                <DragAndDropBeside item={this.props.item} before={true} />
                <div className="item-actions">
                    <button onClick={this.toggleChildren}>{this.state.childrenVisible ? '-' : '+'}</button>
                    <View item={this.props.item} />
                    <Edit item={this.props.item} buttonLabel="Edit" />
                    <button onClick={this.delete}>Delete</button>
                    <Create parent_id={this.props.item.item_id} buttonLabel="Add Child" onCreate={this.fetchChildren} />
                </div>
                <DragAndDropBeside item={this.props.item} after={true} />
                {items}
            </div>
        );
    }
}

Item.contextTypes = {
    menu_id: PropTypes.number
};

export default Item;