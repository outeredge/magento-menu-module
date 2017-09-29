import App from './../App';
import React from 'react';
import { Container } from 'flux/utils';
import ItemStore from './../data/ItemStore';

class AppContainer extends React.Component {
    static getStores() {
        return [ItemStore];
    }

    static calculateState() {
        return {
            items: ItemStore.getState() 
        };
    }

    render() {
        return <App items={this.state.items} {...this.props} />;
    }
};

export default Container.create(AppContainer);
