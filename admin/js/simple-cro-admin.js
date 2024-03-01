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
                croBlock1Slider: {
                    type: 'number',
                    default: '50'
                },
                croBlock2Slider: {
                    type: 'number',
                    default: '50'
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
                const { isNewCRO, isExistCRO, selectedCRO, croTitle, croCat, croTags, croUniqueId, croBlock1Title, croBlock1UniqueId, croBlock2Title, croBlock2UniqueId, croBlock1Slider, croBlock2Slider, selectedPostContent } = attributes;

                const handleNewButtonClick = () => {
                    setAttributes({ isNewCRO: true, isExistCRO: false});
                };
                
                const handleExistingButtonClick = () => {
                    setAttributes({ isExistCRO: true, isNewCRO: false });
                };

                // Handle post selection change
                const handlePostSelectChange = (event) => {
                    const newValue = event.target.value;
                    setAttributes({ selectedCRO: newValue });
                    fetch(`/wp-json/wp/v2/simple_cro/${newValue}`)
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Failed to fetch post content');
                            }
                        })
                        .then(post => {
                            // const postContent = post.content.rendered; 
                            const $postContent = $(post.content.rendered);
                            // console.log(postContent);
                            const innerBlockContent = $postContent.find('.simple-cro-inner-blocks').html();
                            // console.log(innerBlockContent);
                            // Set attributes using jQuery data() method
                            // setAttributes({ selectedPostContent: postContent });
                            setAttributes({ selectedPostContent: innerBlockContent });

                            setAttributes({ croTitle: $(post.content.rendered).data('title') });
                            setAttributes({ croCat: $(post.content.rendered).data('cat') });
                            setAttributes({ croTags: $(post.content.rendered).data('tags') });
                            setAttributes({ croUniqueId: $(post.content.rendered).data('scro-id') }); 
                            setAttributes({ croBlock1Slider: $postContent.find('.simple-cro-inner-blocks').data('block1-percentage') });
                            setAttributes({ croBlock2Slider: $postContent.find('.simple-cro-inner-blocks').data('block2-percentage') });
                            setAttributes({ croBlock1Title: $postContent.find('.simple-cro-inner-blocks').data('block1-title') });
                            setAttributes({ croBlock2Title: $postContent.find('.simple-cro-inner-blocks').data('block2-title') });
                            setAttributes({ croBlock1UniqueId: $postContent.find('.simple-cro-inner-blocks').data('block1-id') });
                            setAttributes({ croBlock2UniqueId: $postContent.find('.simple-cro-inner-blocks').data('block2-id') });
                            setAttributes({ isNewCRO: true });
                            setAttributes({ isExistCRO: false });

                        })
                        .catch(error => {
                            console.error('Error fetching post content:', error);
                        });
                };
                // Handle editing of selected post content
                const handlePostContentEdit = (event) => {
                    const newContent = event.target.innerHTML;
                    console.log(newContent);
                    setAttributes({ selectedPostContent: newContent });
                }
                const currentPostId = wp.data.select('core/editor').getCurrentPostId();

                const SelectedOptions = window.simpleCroBlock && window.simpleCroBlock.posts && window.simpleCroBlock.posts.length > 0
                    ? window.simpleCroBlock.posts.map(post => {
                        if (post.ID !== currentPostId) {
                            return el('option', { key: post.ID, value: post.ID }, post.post_title);
                        }
                        return null; 
                    }).filter(option => option !== null) 
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
                                { className: 'dashicon dashicons dashicons-admin-page' }
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
                                                
                    isNewCRO  && el(
                        'div',
                        { className: 'simple-cro-editor' }, 
                        !isExistCRO && el('div', null,
                            el(InnerBlocks, { allowedBlocks: true })
                        ),
                        el(
                            InspectorControls,
                            null,
                            el(
                                PanelBody,
                                { title: 'CRO Settings', initialOpen: false },
                                el( TextControl, {
                                    label: 'CRO Title',
                                    value: croTitle,
                                    onChange: newValue => props.setAttributes( { croTitle: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'CRO Categories',
                                    value: croCat,
                                    onChange: newValue => props.setAttributes( { croCat: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'CRO Tags',
                                    value: croTags,
                                    onChange: newValue => props.setAttributes( { croTags: newValue } ),
                                    required: true,
                                } ),
                                el( TextControl, {
                                    label: 'CRO Unique Id',
                                    value: croUniqueId,
                                    onChange: newValue => props.setAttributes( { croUniqueId: newValue } ),
                                    required: true,
                                } ),
                                el(
                                    'div',
                                    { className: 'cro-block-distribution' },
                                    el('label', { className: 'cro-block-label' }, 'CRO Block Distribution'),
                                    el(
                                        'div',
                                        { className: 'cro-block-container' },
                                        el('div', { className: 'cro-block' }, 
                                            'Block A',
                                            el('input', {
                                                type: 'number',
                                                value: props.attributes.croBlock1Slider,
                                                onChange: (event) => {
                                                    let newValueA = parseInt(event.target.value);
                                                    let newValueB = 100 - newValueA;
                                                    if (newValueA < 0) newValueA = 0;
                                                    if (newValueA > 100) newValueA = 100;
                                                    props.setAttributes({ croBlock1Slider: newValueA, croBlock2Slider: newValueB });
                                                },
                                            })
                                        ),
                                        el(
                                            'div',
                                            { className: 'cro-slider' },
                                            el(RangeControl, {
                                                value: props.attributes.croBlock1Slider,
                                                onChange: (newValue) => {
                                                    let newValueA = parseInt(newValue);
                                                    let newValueB = 100 - newValueA;
                                                    props.setAttributes({ croBlock1Slider: newValueA, croBlock2Slider: newValueB });
                                                },
                                                min: 0,
                                                max: 100,
                                                step: 10,
                                            })
                                        ),
                                        el('div', { className: 'cro-block' }, 
                                            'Block B',
                                            el('input', {
                                                type: 'number',
                                                value: props.attributes.croBlock2Slider,
                                                onChange: (event) => {
                                                    let newValueB = parseInt(event.target.value);
                                                    let newValueA = 100 - newValueB;
                                                    if (newValueB < 0) newValueB = 0;
                                                    if (newValueB > 100) newValueB = 100;
                                                    props.setAttributes({ croBlock1Slider: newValueA, croBlock2Slider: newValueB });
                                                },
                                            })
                                        )
                                    )
                                )                                                             
                            ),
                            el(
                                PanelBody,
                                { title: 'CRO Block Settings', initialOpen: false },
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
                    // selectedPostContent && el(
                    //     'div',
                    //     { dangerouslySetInnerHTML: { __html: selectedPostContent }}
                    // ),
                    selectedPostContent && el(
                        'div',
                        { 
                            className: 'selected-post-content-container', 
                            contentEditable: true, // Allow content editing
                            // onInput: handlePostContentEdit, 
                            dangerouslySetInnerHTML: { __html: selectedPostContent } 
                        }
                    )
                ];
            },          
            save: function(props) {
                const { 
                    isNewCRO,
                    croTitle, 
                    croCat, 
                    croBlock1Title, 
                    croTags, 
                    croUniqueId, 
                    croBlock1Slider, 
                    croBlock2Slider, 
                    croBlock1UniqueId, 
                    croBlock2Title, 
                    croBlock2UniqueId, 
                    selectedPostContent
                } = props.attributes;
            
                // function getCurrentPage() {
                //     return window.location.pathname;
                // }
            
                // const croWrapperLength = $(".simple-cro-wrapper").length;
                // console.log('Length:', croWrapperLength);
            
                // const currentPage = getCurrentPage();
                // console.log('Current Page:', currentPage);
            
                return (
                        el(
                            'div',
                            { 
                                className: 'simple-cro-wrapper', 
                                'data-title': croTitle, 
                                'data-cat': croCat, 
                                'data-tags': croTags, 
                                'data-scro-id': croUniqueId,
                                'data-scro-device':'',
                                'data-scro-position': '', 
                                'data-scro-variation':'', 
                                'data-scro-unique-id':'' 
                            },               
                            el(
                                'div',
                                {
                                    className: 'simple-cro-inner-blocks',
                                    'data-block1-id': croBlock1UniqueId,
                                    'data-block2-id': croBlock2UniqueId,
                                    'data-block1-title': croBlock1Title,
                                    'data-block2-title': croBlock2Title,
                                    'data-scro-block-position': '', 
                                    'data-scro-block-variation':'',                                     
                                    'data-block1-percentage': croBlock1Slider,
                                    'data-block2-percentage': croBlock2Slider,
                                },
                                el(InnerBlocks.Content)
                            ),
                        selectedPostContent && el(
                            '',
                            { dangerouslySetInnerHTML: { __html: selectedPostContent } }
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
