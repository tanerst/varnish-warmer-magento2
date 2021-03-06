<?php
/**
 * File: Form.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\VarnishWarmer\Block\Adminhtml\PurgeSingle\Form\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form as WidgetForm;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

/**
 * Class Form
 * @package LizardMedia\VarnishWarmer\Block\Adminhtml\PurgeSingle\Form\Edit
 */
class Form extends Generic
{
    const URL_FORM_PARAM = 'url';
    const STORE_VIEW_FORM_PARAM = 'store_id';
    const FORCE_PURGE_FORM_PARAM = 'force_purge';

    /**
     * @var Store
     */
    private $systemStore;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->systemStore = $systemStore;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('purgesingle_form');
        $this->setTitle(__('Varnish: purge single URL'));
    }

    /**
     * @return WidgetForm
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getFormTargerUrl(),
                    'method' => 'post'
                ]
            ]
        );

        $form->setHtmlIdPrefix('purgesingle_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addField(
            self::URL_FORM_PARAM,
            'text',
            [
                'name' => self::URL_FORM_PARAM,
                'label' => __('URL to purge'),
                'title' => __('URL to purge'),
                'required' => false,
                'note' => __('Relative URL, e.g. * or bizuteria. If empty, homepage will be purged')

            ]
        );

        $fieldset->addField(
            self::STORE_VIEW_FORM_PARAM,
            'select',
            [
                'name' => self::STORE_VIEW_FORM_PARAM,
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'values' => $this->systemStore->getStoreValuesForForm(false, true)
            ]
        );

        $fieldset->addField(
            self::FORCE_PURGE_FORM_PARAM,
            'checkbox',
            [
                'name' => self::FORCE_PURGE_FORM_PARAM,
                'label' => __('Force purge'),
                'title' => __('Force purge'),
                'required' => false,
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    private function getFormTargerUrl()
    {
        return $this->_urlBuilder->getUrl('lizardmediavarnish/purgesingle/run');
    }
}
