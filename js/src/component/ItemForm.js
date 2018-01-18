import React from 'react';
import Select from 'react-select';
import Dropzone from 'react-dropzone';
import request from 'superagent';
import { api } from '../helpers/api';

export default class ItemForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            item_id: props.item ? props.item.item_id : null,
            title: props.item ? props.item.title : '',
            description: props.item ? props.item.description : '',
            url: props.item ? props.item.url : '',
            image: props.item ? props.item.image : '',
            product_id: props.item ? props.item.product_id : null,
            category_id: props.item ? props.item.category_id : null,
            use_subcategories: props.item ? props.item.use_subcategories : 0,
            page_id: props.item ? props.item.page_id : null
        };

        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleProductChange = this.handleProductChange.bind(this);
        this.handleCategoryChange = this.handleCategoryChange.bind(this);
        this.handleUseSubcategoriesChange = this.handleUseSubcategoriesChange.bind(this);
        this.handlePageChange = this.handlePageChange.bind(this);

        this.cancel = this.cancel.bind(this);
        this.save = this.save.bind(this);
    }

    handleInputChange(event) {
        const target = event.target;
        this.setState({ [target.name]: target.value });
    }

    handleProductChange(val) {
        this.setState({
            product_id: val ? val.value : null,
            category_id: null,
            page_id: null,
            url: ''
        });
    }

    handleCategoryChange(val) {
        this.setState({
            category_id: val ? val.value : null,
            product_id: null,
            page_id: null,
            url: ''
        });
    }

    handleUseSubcategoriesChange(val) {
        this.setState({
            use_subcategories: val ? val.value : 0
        });
    }

    handlePageChange(val) {
        this.setState({
            page_id: val ? val.value : null,
            product_id: null,
            category_id: null,
            url: ''
        });
    }

    cancel(event) {
        event.preventDefault();
        this.props.onCancel();
    }

    save(event) {
        event.preventDefault();
        this.props.onSave(this.state);
    }

    uploadImage(acceptedFiles) {
        request.post('/store_admin/menu/item/image')
            .field('form_key', document.getElementsByName('form_key')[0].value)
            .attach('image', acceptedFiles[0])
            .end((err, res) => {
                this.setState({ image: res.body.filename });
            });
    }

    openFileBrowser(event) {
        event.preventDefault();
        this.dropzoneRef.open();
    }

    removeImage() {
        this.setState({ image: '' });
    }

    render() {
        const dropzoneStyle = { display: 'none' };

        let image = null;
        if (this.state.image) {
            let imageWrapperStyle = { display: 'flex', alignItems: 'center', marginBottom: '1em' };
            let imageStyle = { maxHeight: 100, marginRight: '1em' };
            image =
                <div style={imageWrapperStyle}>
                    <img style={imageStyle} src={"/media/" + this.state.image} alt="" onClick={this.openFileBrowser.bind(this)} />
                    <button onClick={this.removeImage.bind(this)}>Remove Image</button>
                </div>;
        } else {
            image = <button onClick={this.openFileBrowser.bind(this)}>Upload Image</button>
        }

        let useSubcategories = null;
        if (this.state.category_id) {
            const booleanOptions = [
                { value: 0, label: 'No' },
                { value: 1, label: 'Yes' }
            ];
            useSubcategories =
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Include Subcategories</span>
                    </label>
                    <div className="admin__field-control control">
                        <Select name="use_subcategories" value={this.state.use_subcategories} onChange={this.handleUseSubcategoriesChange} options={booleanOptions} />
                    </div>
                </div>;
        }

        return (
            <form className="item-form">
                <div className="admin__field field required _required">
                    <label className="label admin__field-label">
                        <span>Title</span>
                    </label>
                    <div className="admin__field-control control">
                        <input name="title" type="text" value={this.state.title} onChange={this.handleInputChange} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>URL</span>
                    </label>
                    <div className="admin__field-control control">
                        <input name="url" type="text" value={this.state.url} onChange={this.handleInputChange} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Description</span>
                    </label>
                    <div className="admin__field-control control">
                        <textarea name="description" value={this.state.description} onChange={this.handleInputChange} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Image</span>
                    </label>
                    <div className="admin__field-control control">
                        {image}
                        <Dropzone ref={(dropzone) => { this.dropzoneRef = dropzone; }} multiple={false} style={dropzoneStyle} onDrop={this.uploadImage.bind(this)} />
                        <input name="image" type="hidden" value={this.state.image} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Product</span>
                    </label>
                    <div className="admin__field-control control">
                        <Select.Async name="product_id" value={this.state.product_id} onChange={this.handleProductChange} loadOptions={api.getProducts} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Category</span>
                    </label>
                    <div className="admin__field-control control">
                        <Select.Async name="category_id" value={this.state.category_id} onChange={this.handleCategoryChange} loadOptions={api.getCategories} />
                    </div>
                </div>
                {useSubcategories}
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>Page</span>
                    </label>
                    <div className="admin__field-control control">
                        <Select.Async name="page_id" value={this.state.page_id} onChange={this.handlePageChange} loadOptions={api.getPages} />
                    </div>
                </div>
                <div className="admin__field field">
                    <label className="label admin__field-label">
                        <span>&nbsp;</span>
                    </label>
                    <div className="admin__field-control control">
                        <button className="primary" onClick={this.save}>Save</button>
                        <button onClick={this.cancel}>Cancel</button>
                    </div>
                </div>
            </form>
        );
    }
}
