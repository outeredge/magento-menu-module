import AppContainer from './containers/AppContainer';
import React from 'react';
import ReactDOM from 'react-dom';

const $root = document.getElementById('root');
ReactDOM.render(<AppContainer menu_id={$root.getAttribute('data-menu-id')} />, $root);