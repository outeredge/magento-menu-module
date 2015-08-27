document.observe('dom:loaded', function(){
(function($){

    var page = $('.menu-columns'),
        menu = $('[data-menu]'),
        items = menu.data('menu'),
        url = menu.data('url'),
        formKey = menu.data('form-key'),
        wysiwygConfig = menu.data('wysiwyg-config'),
        activeTab = window.localStorage.getItem('tab') || $('span[data-tab]:first-child').data('tab');

    // Tabs
    $('span[data-tab]').on('click', function(){
        $('[data-tab]').removeClass('active');
        $('[data-tab="' + $(this).data('tab') + '"]').addClass('active');
        window.localStorage.setItem('tab', $(this).data('tab'));
    });
    $('span[data-tab="' + activeTab + '"]').trigger('click');

    // Add tinyMCE to custom link
    var customLinkHtml = new tinyMceWysiwygSetup('html-custom-link', wysiwygConfig);
    Event.observe(window, "load", customLinkHtml.setup.bind(customLinkHtml, "exact"));

    function createMenuItem(data){
        var title = $('<span>', {class: 'title'}).html(data.title);
        var item = $('<li>', {
            'data-id': data.id,
            'data-data': JSON.stringify(data),
            draggable: true
        })
        .append($('<span>', {'data-drop': 'before'}))
        .append($('<span>', {class: 'expand'}))
        .append(
            $('<span>', {class: 'title-image'})
                .append(title)
                .append($('<i>', {class: 'edit'}).html('edit'))
                .append($('<i>', {class: 'delete'}).html('delete'))
        )
        .append($('<span>', {'data-drop': 'after'}));

        if(data.image)
            title.prepend($('<img>', {src: '/media/' + data.image}));

        if(data.before) {
            menu.find('[data-id="' + data.before + '"]').before(item);
        }
        else if(data.after) {
            menu.find('[data-id="' + data.after + '"]').after(item);
        }
        else {
            var parent = data.parent ? menu.find('[data-id="' + data.parent + '"]') : menu;
            parent.append(item);
        }
    }

    function createForm(data){
        var form = $('<form>', {enctype: 'multipart/form-data'});
        form.append($('<input>', {type: 'hidden', name: 'id', value: data.id}));
        form.append($('<div>').append($('<label>').html('Title')).append($('<input>', {type: 'text', name: 'title', value: data.title})));
        form.append($('<div>')
            .append($('<label>').html('Image'))
            .append($('<input>', {type: 'file', name: 'image'}))
            .append($('<div>', {class: 'delete-image'})
                .append($('<input>', {type: 'checkbox', name: 'image[delete]', value: 1}))
                .append($('<i>').html('Delete Image'))
            )
        );
        if(data.type === 'custom') {
            form.append($('<div>').append($('<label>').html('URL')).append($('<input>', {type: 'text', name: 'url', value: data.url})));
            form.append($('<div>').append($('<label>').html('HTML Block')).append($('<input>', {type: 'checkbox', name: 'is_html', checked: data.is_html === "1"})));
            form.append($('<div>', {class: 'html'}).append($('<label>').html('HTML')).append($('<textarea>', {id: 'html-' + data.id, name: 'html'}).val(data.html)));
            if(data.is_html === "1")
                form.addClass('html-visible');
        } else {
            form.append($('<div>').append($('<label>').html(data.type)).append($('<input>', {type: 'text', disabled: 'disabled', value: data.entity_id})));
        }
        form.append($('<button>', {type: 'submit'}).html('Save Changes'));
        form.append($('<span>', {class: 'close'}));
        return form;
    }

    function formatExpander(){
        menu.find('[data-id]').each(function(){
            $(this).toggleClass('has-children', $(this).find('> [data-id]').length > 0);
        });
    }

    $(items).each(function(){
        createMenuItem(this);
    });
    formatExpander();

    menu.on('click', '.expand', function(e){
        $(this).parent().toggleClass('expanded');
    });

    menu.on('click', '.delete', function(){
        if(!window.confirm('Are you sure you want to delete this item? All children will also be deleted.'))
            return;

        var item = $(this).closest('[data-id]');
        $.ajax({
            method: 'POST',
            url: url + 'delete',
            data: {
                id: item.data('id'),
                form_key: formKey
            }
        }).done(function(){
            item.remove();
        });
    });

    menu.on('click', '.edit', function(){
        var item = $(this).closest('[data-id]');
        var data = item.data('data');
        item.find('> form').remove();
        item.find('> .title-image').after(createForm(data));
        tinyMCE.execCommand('mceRemoveControl', false, 'html-' + item.data('id'));
        setTimeout(function(){
            tinyMCE.execCommand('mceAddControl', false, 'html-' + item.data('id'));
        });
    });

    page.on('change', '[name="is_html"]', function(){
        $(this).closest('form').toggleClass('html-visible');
    });

    menu.on('submit', 'form', function(e){
        e.preventDefault();

        tinyMCE.triggerSave();
        $(this).append($('<input>', {type: 'hidden', name: 'form_key', value: formKey}));
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: url + 'update',
            data: formData,
            contentType: false,
            processData: false
        }).done(function(data){
            var item = $('[data-id="' + data.id + '"]'),
                title = item.find('> span > .title');
            title.html(data.title);
            title.find('img').remove();
            if(data.image)
                title.prepend($('<img>', {src: '/media/' + data.image}));
            item.find('> form').remove();
            item.data('data', data);
            tinyMCE.execCommand('mceRemoveControl', false, 'html-' + data.id);
        });
    });

    menu.on('click', '.close', function(){
        $(this).closest('form').remove();
        tinyMCE.execCommand('mceRemoveControl', false, 'html-' + $(this).closest('[data-id]').data('id'));
    });

    menu.on('dragstart', '[data-id]', function(e){
        e.stopPropagation();
        e.originalEvent.dataTransfer.setData('id', $(this).data('id'));
        e.originalEvent.dataTransfer.setData('sorting', true);
    });
    menu.on('dragleave', '.title-image, [data-drop]', function(){
        $('*').removeClass('dropover');
    });
    menu.on('dragover', '.title-image, [data-drop]', function(e){
        e.preventDefault();
        $(this).addClass('dropover');
    });
    menu.on('drop', '[data-drop]', function(e){
        e.preventDefault();
        var parent = null;
        if($(this).closest('[data-id]'))
            parent = $(this).closest('[data-id]').data('data').parent;

        if($(this).data('drop') === 'before'){
            dropAction(e, parent, {before: $(this).closest('[data-id]').data('id')});
        } else {
            dropAction(e, parent, {after: $(this).closest('[data-id]').data('id')});
        }
    });
    menu.on('drop', '.title-image', function(e){
        e.preventDefault();
        var parent = null;
        if($(this).closest('[data-id]'))
            parent = $(this).closest('[data-id]').data('id');
        dropAction(e, parent);
    });

    function dropAction(e, parent, sortingData){
        var data = e.originalEvent.dataTransfer;
        if(data.getData('customForm')) {
            tinyMCE.triggerSave();
            var form = $('#custom-link-form').get(0);
            $(form).append($('<input>', {type: 'hidden', name: 'form_key', value: formKey}));
            $(form).append($('<input>', {type: 'hidden', name: 'parent', value: parent}));
            if(sortingData)
                $.each(sortingData, function(key,val){
                    $(form).append($('<input>', {type: 'hidden', name: 'sorting[' + key + ']', value: val}));
                });
            var formData = new FormData(form);

            $.ajax({
                method: 'POST',
                url: url + 'create',
                data: formData,
                contentType: false,
                processData: false
            }).done(function(data){
                $('*').removeClass('dropover');
                createMenuItem(data);
                $(form).trigger('reset').removeClass('html-visible');
            });
        }
        else if(data.getData('sorting')) {
            $.ajax({
                method: 'POST',
                url: url + 'parent',
                data: {
                    id: data.getData('id'),
                    parent: parent,
                    form_key: formKey,
                    sorting: sortingData
                }
            }).done(function(data){
                if(data.before) {
                    menu.find('[data-id="' + data.before + '"]').before(menu.find('[data-id="' + data.id + '"]'));
                }
                else if(data.after) {
                    menu.find('[data-id="' + data.after + '"]').after(menu.find('[data-id="' + data.id + '"]'));
                }
                else {
                    var appendTo = menu.find('[data-id="' + data.parent + '"]');
                    menu.find('[data-id="' + data.id + '"]').appendTo(appendTo);
                    appendTo.addClass('has-children expanded');
                }
                $('*').removeClass('dropover');
            });
        }
        else {
            $.ajax({
                method: 'POST',
                url: url + 'create',
                data: {
                    id: data.getData('id'),
                    type: data.getData('type'),
                    parent: parent,
                    form_key: formKey,
                    sorting: sortingData
                }
            }).done(function(data){
                $('*').removeClass('dropover');
                createMenuItem(data);
            });
        }
    }

    $('[data-tab="categories_tree"] span').attr('draggable', true);
    page.on('dragstart', '[data-tab="categories_tree"] span', function(e){
        e.originalEvent.dataTransfer.setData('id', $(this).data('id'));
        e.originalEvent.dataTransfer.setData('type', 'category');
    });

    $('#productGrid_table tbody tr').attr('draggable', true);
    page.on('dragstart', '#productGrid_table tbody tr', function(e){
        e.originalEvent.dataTransfer.setData('id', $(this).find('input[name="product"]').val());
        e.originalEvent.dataTransfer.setData('type', 'product');
    });

    $('#cmsPageGrid_table tbody tr').attr('draggable', true);
    page.on('dragstart', '#cmsPageGrid_table tbody tr', function(e){
        e.originalEvent.dataTransfer.setData('id', parseInt($(this).attr('title').match(/page_id\/(\d+)/)[1]));
        e.originalEvent.dataTransfer.setData('type', 'cms');
    });

    page.on('dragstart', '.drag-create', function(e){
        if(!$(this).closest('form').find('[name="title"]').val()) {
            alert('You must enter a title before creating.');
            return false;
        }
        e.originalEvent.dataTransfer.setData('customForm', true);
    });

})(jQuery);
});