<?php

class Edge_Menu_Block_Adminhtml_Custom_Link extends Mage_Adminhtml_Block_Widget_Form
{
    public function getFormHtml()
    {
        if (is_object($this->getForm())) {
            return $this->getForm()->getHtml();
        }
        return '';
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'      => 'custom-link-form',
            'enctype' => 'multipart/form-data'
        ));
        $form->setUseContainer(true);

        $fieldset = $form->addFieldset('content_fieldset', array(
            'class'  => 'fieldset-wide'
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField('type', 'hidden', array(
            'name'  => 'type',
            'value' => 'custom'
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('menu')->__('Title'),
            'name'  => 'title'
        ));

        $fieldset->addField('url', 'text', array(
            'label' => Mage::helper('menu')->__('URL'),
            'name'  => 'url'
        ));

        $fieldset->addField('class', 'text', array(
            'label' => Mage::helper('menu')->__('Class'),
            'name'  => 'class'
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('menu')->__('Image'),
            'name'  => 'image'
        ));

        $htmlToggle = $fieldset->addField('create:is_html', 'select', array(
            'label'  => Mage::helper('menu')->__('HTML Block'),
            'name'   => 'is_html',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
        ));

        $html = $fieldset->addField('html_custom_link', 'editor', array(
            'label'  => Mage::helper('menu')->__('HTML'),
            'name'   => 'html',
            'config' => $wysiwygConfig
        ));

        $button = $fieldset->addField('drag_create', 'button', array());
        $button->setRenderer($this->getLayout()->createBlock('menu/adminhtml_custom_link_button'));

        $this->setForm($form);

        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($html->getHtmlId(), $html->getName())
            ->addFieldMap($htmlToggle->getHtmlId(), $htmlToggle->getName())
            ->addFieldDependence($html->getName(), $htmlToggle->getName(), 1));

        return parent::_prepareForm();
    }
}