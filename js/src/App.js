import React from 'react';
import PropTypes from 'prop-types';
import { api } from './helpers/api';
import ItemActions from './data/ItemActions';
import Create from './component/Create';
import DragAndDropItem from './component/DragAndDrop/Item';
import Item from './component/Item';
import HTML5Backend from 'react-dnd-html5-backend';
import { DragDropContext } from 'react-dnd';

function App(props) {
    return <Menu {...props} />;
}

@DragDropContext(HTML5Backend)
class Menu extends React.Component {
    render() {
        return <Main {...this.props} />;
    }
};

function Header() {
    return (
        <header className="menu-header">
            <h2>Menu Items</h2>
            <Create buttonLabel="Add Item" />
        </header>
    );
};

class Main extends React.Component {
    getChildContext() {
        return {
            menu_id: parseInt(this.props.menu_id)
        };
    }

    componentDidMount() {
        api.getItems(this.props.menu_id).then(res => {
            res.items.forEach(item => {
                ItemActions.addItem(item);
            });
        });
    }

    render() {
        let items;
        if (this.props.items.size) {
            items = [];
            [...this.props.items.values()].map(item => {
                items.push(<DragAndDropItem item={item} key={item.item_id} />);
            });
        } else {
            items = <span>Use the button above to add the first menu item</span>;
        }
        return (
            <div className="menu-editor">
                <Header />
                <section>
                    {items}
                </section>
            </div>
        );
    }
};

Main.childContextTypes = {
    menu_id: PropTypes.number
};

export default App;