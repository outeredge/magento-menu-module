import React from 'react';
import { api } from '../helpers/api';
import ItemActions from './../data/ItemActions';
import ItemForm from './ItemForm';

export default class Edit extends React.PureComponent {

    constructor(props) {
        super(props);
        this.state = {
            isActive: false
        };

        this.edit = this.edit.bind(this);
        this.cancel = this.cancel.bind(this);
        this.save = this.save.bind(this);
    }

    edit() {
        this.setState({ isActive: true });
    }

    cancel() {
        this.setState({ isActive: false });
    }

    save(item) {
        api.saveItem(item).then(item => {
            ItemActions.updateItem(item);
            this.setState({ isActive: false });
        });
    }

    render() {
        if (this.state.isActive) {
            return <ItemForm item={this.props.item} onCancel={this.cancel} onSave={this.save} />
        }
        return <button onClick={this.edit}>{this.props.buttonLabel}</button>
    }
}
