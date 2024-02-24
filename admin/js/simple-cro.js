(function( $ ) {
    'use strict';
    var __ = wp.i18n.__;
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var Button = wp.components.Button;
    var TextControl = wp.components.TextControl;
    var RangeControl = wp.components.RangeControl;
    var PanelBody = wp.components.PanelBody;
    var InnerBlocks = wp.blockEditor.InnerBlocks; // Import InnerBlocks

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
            isExitsCRO: {
                type: 'boolean',
                default: false
            },
            selectedCRO: {
                type: 'string',
                default: ''
            },
            selectedPostContent:{
                type: 'string',
                default: ''
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
            const { isNewCRO, isExitsCRO, selectedCRO, selectedPostContent} = attributes;

            const handleNewButtonClick = () => {
                setAttributes({ isNewCRO: true, isExitsCRO: false });
            };

            const handleExistingButtonClick = () => {
                setAttributes({ isExitsCRO: true, isNewCRO: false });
            };

            const handlePostSelectChange = (event) => {
                const newValue = event.target.value;
                setAttributes({ selectedCRO: newValue });
            
                // Find the selected post based on the ID
                const selectedPost = window.simpleCroBlock.posts.find(post => post.ID === parseInt(newValue));
                
                if (selectedPost) {
                    // Access the content of the selected post
                    const postContent = selectedPost.post_content;
                    // Set the selected post content and block type as attributes
                    setAttributes({ selectedPostContent: postContent});              
                }      
                console.log('selectedPost:', selectedPost);
          
            };
            const SelectedOptions = window.simpleCroBlock && window.simpleCroBlock.posts && window.simpleCroBlock.posts.length > 0
            ? window.simpleCroBlock.posts.map(post => el('option', { key: post.ID, value: post.ID }, post.post_title))
            : [];
            return [
                // Buttons to switch between New CRO and Existing CRO
                el(
                    'div',
                    { className: 'simple-cro-buttons' },
                    el(
                        'h3',
                        null,
                        'Simple CRO'
                    ),                    
                    el( 
                        'p',
                        null,
                        'Simplify Gutenberg block testing with the Simple CRO'
                    ),
                    el( Button, {
                        isPrimary: isNewCRO,
                        onClick: handleNewButtonClick,
                    }, 'New CRO' ),
                    el( Button, {
                        isPrimary: isExitsCRO,
                        onClick: handleExistingButtonClick,
                    }, 'Existing CRO'),
                ),     
                isExitsCRO && el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Simple CRO Select' },
                        el(
                            'div',
                            { className: 'simple-cro-post-select' },
                            el(
                                'select',
                                { value: selectedCRO, onChange: handlePostSelectChange },
                                el(
                                    'option',
                                    { value: '' },
                                    'Select a Post'
                                ),
                                SelectedOptions
                            ),
                        ),
                    )
                ),                                                  
                isNewCRO && el(
                    'div',
                    { className: 'simple-cro-editor' }, 
                    el( InnerBlocks, { allowedBlocks: [ 'core/paragraph', 'core/image', 'core/audio' ] } ),
                    // Select dropdown for existing CRO
           
                    // Options panel in the right sidebar
                    el(
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
                )
            ]},

            save: function( props ) {
                const { isNewCRO, isExitsCRO, selectedPostContent } = props.attributes;
            
                // If it's a new CRO, render only the InnerBlocks content
                if (isNewCRO) {
                    return (
                        el( 'div', { className: 'simple-cro-wrapper' },
                            el( InnerBlocks.Content )
                        )
                    );
                } else {
                    // Otherwise, render the selected post content
                    return (
                        el( 'div', { className: 'simple-cro-wrapper' },
                            el( 'div', { className: 'selected-post-content', dangerouslySetInnerHTML: { __html: selectedPostContent } } )
                        )
                    );
                }
            }
                     
    });  
    
})( jQuery );          
