import Edit from './Edit';
import PropTypes from 'prop-types';
import { saveItem } from '../helpers/api';
import ItemActions from './../data/ItemActions';
import ItemStore from './../data/ItemStore';

class Create extends Edit {
    save(item) {
        Object.assign(item, {
            menu_id: this.context.menu_id,
            parent_id: this.props.parent_id
        });

        const siblings = ItemStore.getState().getIn(ItemStore.getPath(item, true));
        if (siblings.size) {
            item.sort_order = siblings.sort((a, b) => {
                if (a.sort_order < b.sort_order) { return -1; }
                if (a.sort_order > b.sort_order) { return 1; }
                return 0;
            }).last().sort_order + 1;
        } else {
            item.sort_order = 1;
        }

        saveItem(item).then(item => {
            ItemActions.addItem(item);
            this.setState({ isActive: false });
            if (this.props.onCreate) {
                this.props.onCreate();
            }
        });
    }
};

Create.contextTypes = {
    menu_id: PropTypes.number
};

export default Create;