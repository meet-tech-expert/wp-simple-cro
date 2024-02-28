(function( $ ) {
    'use strict';

    (function(blocks, editor, i18n, element, components, blockEditor) {

        var __ = i18n.__;
        var el = element.createElement;
        var registerBlockType = blocks.registerBlockType;
        var InspectorControls = blockEditor.InspectorControls;
        var Button = components.Button;
        var TextControl = components.TextControl;
        var RangeControl = components.RangeControl;
        var PanelBody = components.PanelBody;
        var InnerBlocks = blockEditor.InnerBlocks; 

        registerBlockType( 'simple-cro/block', {
            title: __( 'Simple CRO', 'wp-simple-cro' ),
            description: __( 'A simple CRO description','wp-simple-cro' ),
            icon: 'admin-page',
            category: 'widgets',
            className: 'simple-cro',

            attributes: {
                isNewCRO: {
                    type: 'boolean',
                    default: false
                },
                isExistCRO: {
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
                croTitle: {
                    type: 'string',
                    default: ''
                },
                croCat: {
                    type: 'string',
                    default: ''
                },
                croTags: {
                    type: 'string',
                    default: ''
                },
                croUniqueId: {
                    type: 'string',
                    default: ''
                },
                croSlider: {
                    type: 'number',
                    default: 50
                },
                croBlock1Title: {
                    type: 'string',
                    default: ''
                },
                croBlock1UniqueId: {
                    type: 'string',
                    default: ''
                },
                croBlock2Title: {
                    type: 'string',
                    default: ''
                },
                croBlock2UniqueId: {
                    type: 'string',
                    default: ''
                },
            },

            edit: function( props ) {
                const { attributes, setAttributes } = props;            
                // const { isNewCRO, isExistCRO, selectedCRO, selectedPostContent} = attributes;
                const { isNewCRO, isExistCRO, selectedCRO, croTitle, croCat, croTags, croUniqueId, croBlock1Title, croBlock1UniqueId, croBlock2Title, croBlock2UniqueId,selectedPostContent } = attributes;

                const handleNewButtonClick = () => {
                    setAttributes({ isNewCRO: true, isExistCRO: false});
                };
                
                const handleExistingButtonClick = () => {
                    setAttributes({ isExistCRO: true, isNewCRO: true });
                };
                const handlePostSelectChange = (event) => {
                    const newValue = event.target.value;
                    setAttributes({ selectedCRO: newValue });
                    //console.log(newValue);
                    // Make a request to the WordPress REST API to fetch post content
                    fetch(`/wp-json/wp/v2/simple_cro/${newValue}`)
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Failed to fetch post content');
                            }
                        })
                        .then(post => {
                            // Access the content of the selected post
                            const postContent = post.content.rendered;
                            // // Set the selected post content as attributes
                            setAttributes({ selectedPostContent: postContent }); 
                            setAttributes( { croTitle: $(post.content.rendered).data('title') } )
                            setAttributes( { croCat: $(post.content.rendered).data('cat') } )
                            setAttributes( { croTags: $(post.content.rendered).data('tags') } )
                            setAttributes( { croUniqueId: $(post.content.rendered).data('id') } )
                            setAttributes( { croSlider: $(post.content.rendered).data('slider') } )
                        })
                        .catch(error => {
                            console.error('Error fetching post content:', error);
                        });
                };
                
                const SelectedOptions = window.simpleCroBlock && window.simpleCroBlock.posts && window.simpleCroBlock.posts.length > 0
                ? window.simpleCroBlock.posts.map(post => el('option', { key: post.ID, value: post.ID }, post.post_title))
                : [];
                
                return [
                    el(
                        'div',
                        { className: 'simple-cro-blocks components-placeholder is-large' },
                        el(
                            'div',
                            { className: 'components-placeholder__label' }, 
                            el(
                                'span',
                                {className: 'dashicon dashicons dashicons-admin-page'}
                            ),
                            'Simple CRO',
                            
                        ),
                        !isNewCRO && !isExistCRO && el( 
                            'div',
                            { className: 'components-placeholder__instructions' },
                            'A simple CRO description'
                        ),
                        !isNewCRO && !isExistCRO && el(
                            'div',
                            { className: 'simple-cro-buttons components-placeholder__fieldset' },
                        el( Button, {
                            className: 'primary',
                            isPrimary: true,
                            onClick: handleNewButtonClick,
                        }, 'New CRO' ),
                        el( Button, {
                            isSecondary: true,
                            onClick: handleExistingButtonClick,
                        }, 'Existing CRO')
                        ),
                    ),
                    isExistCRO && el(
                        InspectorControls,
                        null,
                        el(
                            PanelBody,
                            { title: 'Existing CRO' },
                            el(
                                'div',
                                { className: 'simple-cro-post-select' },
                                el(
                                    'select',
                                    { value: selectedCRO, onChange: handlePostSelectChange },
                                    el(
                                        'option',
                                        { value: '' },
                                        'Select CRO'
                                    ),
                                    SelectedOptions
                                ),
                            ),
                        ),
                    ),  
                                                
                    isNewCRO && el(
                        'div',
                        { className: 'simple-cro-editor' }, 
                        !isExistCRO && el( InnerBlocks, { allowedBlocks: true,
                         } ),
                        el(
                            InspectorControls,
                            null,
                            el(
                                PanelBody,
                                {
                                    title: 'CRO Settings',
                                    initialOpen: false,
                                },
                                el( TextControl, {
                                    label: 'CRO Title',
                                    value: croTitle,
                                    onChange: newValue => props.setAttributes( { croTitle: newValue } ),
                                } ),
                                el( TextControl, {
                                    label: 'CRO Categories',
                                    value: croCat,
                                    onChange: newValue => props.setAttributes( { croCat: newValue } ),
                                } ),
                                el( TextControl, {
                                    label: 'CRO Tags',
                                    value: croTags,
                                    onChange: newValue => props.setAttributes( { croTags: newValue } ),
                                } ),
                                el( TextControl, {
                                    label: 'CRO Unique Id',
                                    value: croUniqueId,
                                    onChange: newValue => props.setAttributes( { croUniqueId: newValue } ),
                                } ),
                                el( RangeControl, {
                                    label: 'Slider to adjust block frequency',
                                    value: props.attributes.croSlider,
                                    onChange: newValue => props.setAttributes( { croSlider: newValue } ),
                                    min: 0,
                                    max: 100,
                                    step: 1,
                                } )
                            ),
                            el(
                                PanelBody,
                                {
                                    title: 'CRO Block Settings',
                                    initialOpen: false,
                                },
                                el( TextControl, {
                                    label: 'Block 1 Title',
                                    value: croBlock1Title,
                                    onChange: newValue => props.setAttributes( { croBlock1Title: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'Block 1 Unique Id',
                                    value: croBlock1UniqueId,
                                    onChange: newValue => props.setAttributes( { croBlock1UniqueId: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'Block 2 Title',
                                    value: croBlock2Title,
                                    onChange: newValue => props.setAttributes( { croBlock2Title: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'Block 2 Unique Id',
                                    value: croBlock2UniqueId,
                                    onChange: newValue => props.setAttributes( { croBlock2UniqueId: newValue } ),
                                    required: true,
                                } ),
                            )
                        )
                    ),
                    selectedPostContent && el(
                        'div',
                        { className: 'simple-cro-content', dangerouslySetInnerHTML: { __html: selectedPostContent }}
                    ),
            ]},          
            save: function(props) {

                const { croTitle, croCat , croBlock1Title, croTags, croUniqueId,croSlider, croBlock1UniqueId, croBlock2Title, croBlock2UniqueId , selectedPostContent} = props.attributes;
            
                return (
                    el(
                        'div',
                        { className: 'simple-cro-wrapper', 'data-title': croTitle, 'data-cat': croCat, 'data-tags': croTags, 'data-id': croUniqueId, 'data-slider': croSlider },
                        el(wp.blockEditor.InnerBlocks.Content),
                        selectedPostContent && el(
                            'div',
                            { className: 'simple-cro-content', dangerouslySetInnerHTML: { __html: selectedPostContent } }
                        )
                    )
                );
            }
                                
        });    
    })(
        window.wp.blocks,
        window.wp.editor,
        window.wp.i18n,
        window.wp.element,
        window.wp.components,
        window.wp.blockEditor
    );     
})( jQuery );
