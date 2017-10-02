import React from 'react';

export default class View extends React.PureComponent {
    render() {
        return (
            <div className="item-data">
                <div className="title">{this.props.item.title}</div>
                {this.props.item.description && <div className="description">{this.props.item.description}</div>}
                {this.props.item.url && <div className="url">{this.props.item.url}</div>}
                {this.props.item.product_id && <div className="product">Product: {this.props.item.product_id}</div>}
                {this.props.item.category_id && <div className="category">Category: {this.props.item.category_id}</div>}
                {this.props.item.page_id && <div className="category">Page: {this.props.item.page_id}</div>}
            </div>
        )
    }
}