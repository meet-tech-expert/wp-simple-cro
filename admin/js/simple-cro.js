(function( $ ) {
    'use strict';
    var __ = wp.i18n.__;
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var Button = wp.components.Button;
    var TextControl = wp.components.TextControl;
    var RangeControl = wp.components.RangeControl;

    registerBlockType( 'simple-cro/block', {
        title: __( 'Simple CRO', 'wp-simple-cro' ),
        icon: 'admin-settings',
        category: 'layout',
        className: 'simple-cro',

        attributes: {
            isNewCRO: {
                type: 'boolean',
                default: false
            },
            croTestTitle: {
                type: 'string',
                default: ''
            },
            croCategory: {
                type: 'string',
                default: ''
            },
            croBlockUniqueId: {
                type: 'string',
                default: ''
            },
            blockFrequency: {
                type: 'number',
                default: 50
            }
        },

        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { isNewCRO } = attributes;

            const handleNewButtonClick = () => {
                setAttributes({ isNewCRO: true });
            };

            const handleExistingButtonClick = () => {
                setAttributes({ isNewCRO: false });
            };

            return [
               
                // Buttons to switch between New CRO and Existing CRO
                el(
                    'div',
                    { className: 'simple-cro-buttons' },
                    el(
                        'h3',
                        { className: props.className },
                        ' Simple CRO',
                    ),
                    el(
                        'p',
                        { className: props.className },
                        ' Simplify Gutenberg block testing with the Simple CRO',
                    ),
                    el( Button, {
                        isPrimary: isNewCRO,
                        onClick: handleNewButtonClick,
                    }, 'New CRO' ),
                    el( Button, {
                        isPrimary: !isNewCRO,
                        onClick: handleExistingButtonClick,
                    }, 'Existing CRO' )
                ),
                // Options panel in the right sidebar
                isNewCRO && el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        {
                            title: 'Block Options',
                            initialOpen: true,
                        },
                        el( TextControl, {
                            label: 'CRO Test Title',
                            value: props.attributes.croTestTitle,
                            onChange: newValue => props.setAttributes( { croTestTitle: newValue } ),
                        } ),
                        el( TextControl, {
                            label: 'CRO Category',
                            value: props.attributes.croCategory,
                            onChange: newValue => props.setAttributes( { croCategory: newValue } ),
                        } ),
                        el( TextControl, {
                            label: 'CRO Block Unique Id',
                            value: props.attributes.croBlockUniqueId,
                            onChange: newValue => props.setAttributes( { croBlockUniqueId: newValue } ),
                        } ),
                        el( RangeControl, {
                            label: 'Slider to adjust block frequency',
                            value: props.attributes.blockFrequency,
                            onChange: newValue => props.setAttributes( { blockFrequency: newValue } ),
                            min: 0,
                            max: 100,
                            step: 1,
                        } )
                    )
                )
            ];            
        },

        save: () => {
            return null; // No content saved on the front end
        },
    } );
    
})( jQuery );
