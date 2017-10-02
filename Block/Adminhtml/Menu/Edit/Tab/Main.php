<?php

namespace OuterEdge\Menu\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;

class Main extends Generic
{
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $menu = $this->_coreRegistry->registry('menu');

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Menu Properties')]);

        if ($menu->getId()) {
            $fieldset->addField('menu_id', 'hidden', ['name' => 'menu_id']);
        }

        $fieldset->addField(
            'code',
            'text',
            [
                'name'     => 'code',
                'label'    => __('Code'),
                'title'    => __('Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name')
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'  => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order')
            ]
        );

        $form->setValues($menu->getData());
        $this->setForm($form);

        $this->_eventManager->dispatch('menu_form_build_main_tab', ['form' => $form]);

        return parent::_prepareForm();
    }
}
