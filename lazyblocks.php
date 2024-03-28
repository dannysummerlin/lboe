add_action( 'lzb/init', function() {

    lazyblocks()->add_block( array(
        'id' => 81423,
        'title' => 'Dynamic Text Reveal',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M9 10v5h2V4h2v11h2V4h2V2H9C6.79 2 5 3.79 5 6s1.79 4 4 4zm12 8l-4-4v3H5v2h12v3l4-4z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/dynamic-text-reveal',
        'description' => 'WORK IN PROGRESS',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_1dc8794f89' => array(
                'type' => 'text',
                'name' => 'label',
                'default' => '',
                'label' => 'Label',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_7528174005' => array(
                'type' => 'image',
                'name' => 'image',
                'default' => '',
                'label' => 'Image',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'true',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'insert_from_url' => 'true',
                'preview_size' => 'medium',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_ea58b14379' => array(
                'type' => 'radio',
                'name' => 'image-label-layout',
                'default' => 'top',
                'label' => 'Image/Label Layout',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Bottom',
                        'value' => 'bottom',
                    ),
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Top',
                        'value' => 'top',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_b2fb474a95' => array(
                'type' => 'radio',
                'name' => 'animate-from',
                'default' => 'bottom',
                'label' => 'Animate From',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Bottom',
                        'value' => 'bottom',
                    ),
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Top',
                        'value' => 'top',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_5c4bfc480e' => array(
                'type' => 'color',
                'name' => 'overlay-background-color',
                'default' => '#000a',
                'label' => 'Overlay Background Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'true',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_e938fb434d' => array(
                'type' => 'radio',
                'name' => 'user-action',
                'default' => 'hover',
                'label' => 'User Action',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Hover',
                        'value' => 'hover',
                    ),
                    array(
                        'label' => 'Click',
                        'value' => 'click',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '<div>
        <div class="content-shown">
            <small>Base Content</small>
            <?php
                switch($attributes[\'image-label-layout\']) {
                    case \'top\':
                    case \'left\':
                        echo $attributes[\'image\'].\'<h4>\'.$attributes[\'label\'].\'</h4>\';
                        break;
                    case \'bottom\':
                    case \'right\':
                        echo \'<h4>\'.$attributes[\'label\'].\'</h4>\'.$attributes[\'image\'];
                        break;
                }
            ?>
        </div>
        <div class="content-hidden">
            <h2>Content to Reveal</h2>
            <InnerBlocks />
        </div>
    </div>',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $blockId = \'reveal\'.rand(100,999); ?>
    <style>
        #<?= $blockId ?> {
            .content-hidden {
                margin-<?= $attributes[\'animate-from\'] ?>: 10000px;
            }
        }
        #<?= $blockId ?><?= ($attributes[\'user-action\'] != \'hover\') ? \'.content-reveal\' : \':hover\' ?> {
            .content-hidden {
                margin-<?= $attributes[\'animate-from\']?>: 0;
            }
        }
    </style>
    <div id="<?= $blockId ?>" class="dynamic-reveal">
        <div class="content-shown image-layout-<?= $attributes[\'image-label-layout\'] ?>">
            <?php
                switch($attributes[\'image-label-layout\']) {
                    case \'top\':
                    case \'left\':
                        echo $attributes[\'image\'].\'<h4>\'.$attributes[\'label\'].\'</h4>\';
                        break;
                    case \'bottom\':
                    case \'right\':
                        echo \'<h4>\'.$attributes[\'label\'].\'</h4>\'.$attributes[\'image\'];
                        break;
                }
            ?>
        </div>
        <div class="content-hidden">
            <InnerBlocks />
        </div>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 81222,
        'title' => 'Image - Jumpstart Style',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/image-jumpstart-style',
        'description' => '',
        'category' => 'media',
        'category_label' => 'media',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_6feac044fa' => array(
                'type' => 'image',
                'name' => 'image',
                'default' => '',
                'label' => 'Image',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'insert_from_url' => 'true',
                'preview_size' => 'full',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_0078704efa' => array(
                'type' => 'select',
                'name' => 'resolution',
                'default' => 'medium',
                'label' => 'Resolution',
                'help' => 'WordPress doesn\'t standardize resolution names well, you may have to click around to find a good fit per image',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Large',
                        'value' => 'large',
                    ),
                    array(
                        'label' => 'Medium Large',
                        'value' => 'medium_large',
                    ),
                    array(
                        'label' => 'Medium Large alternative',
                        'value' => 'neve-blog',
                    ),
                    array(
                        'label' => 'Medium',
                        'value' => 'medium',
                    ),
                    array(
                        'label' => 'Thumbnail',
                        'value' => 'thumbnail',
                    ),
                    array(
                        'label' => 'Full-size',
                        'value' => 'fullsize',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_dd48724d16' => array(
                'type' => 'range',
                'name' => 'height',
                'default' => '4',
                'label' => 'Height',
                'help' => 'measured in REM units',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '40',
                'step' => '0.2',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_7bd90340c1' => array(
                'type' => 'range',
                'name' => 'width',
                'default' => '',
                'label' => 'Width',
                'help' => 'leave blank to take up the full width of the element; measured in REM units',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '200',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_65dbc64418' => array(
                'type' => 'range',
                'name' => 'initial-zoom',
                'default' => '100',
                'label' => 'Initial Zoom',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '100',
                'max' => '2000',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_9a8b8a4454' => array(
                'type' => 'range',
                'name' => 'position-horizontal',
                'default' => '50',
                'label' => 'Position - Horizontal',
                'help' => 'Percentage from center of block',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c2eb764e23' => array(
                'type' => 'range',
                'name' => 'position-vertical',
                'default' => '50',
                'label' => 'Position - Vertical',
                'help' => 'Percentage from center of block',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_3b689842a1' => array(
                'type' => 'range',
                'name' => 'rounding-radius',
                'default' => '0',
                'label' => 'Rounding Radius',
                'help' => 'leave blank or set to 0 to have it auto-size itself',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '200',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_e0f9b643ce' => array(
                'type' => 'toggle',
                'name' => 'zoom-on-hover',
                'default' => '',
                'label' => 'Zoom on Hover',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => 'Zoom image on mouseover',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_50ba0b4b2c' => array(
                'type' => 'toggle',
                'name' => 'has-frame',
                'default' => '',
                'label' => 'Has Frame',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => 'Show Jumpstart brand frame',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_9a38154916' => array(
                'type' => 'select',
                'name' => 'frame-color',
                'default' => 'var(--jsdarkblue)',
                'label' => 'Frame Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Dark Blue',
                        'value' => 'var(--jsdarkblue)',
                    ),
                    array(
                        'label' => 'Blue',
                        'value' => 'var(--jsblue)',
                    ),
                    array(
                        'label' => 'Red',
                        'value' => 'var(--jsred)',
                    ),
                    array(
                        'label' => 'Pink',
                        'value' => 'var(--jspink)',
                    ),
                    array(
                        'label' => 'Mauve',
                        'value' => 'var(--jsmauve)',
                    ),
                    array(
                        'label' => 'White',
                        'value' => 'white',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6e29e54271' => array(
                'type' => 'url',
                'name' => 'link',
                'default' => '',
                'label' => 'Link',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '<?php
    if($attributes[\'image\']) {
        $blockId = \'jsImage\'. rand(1000,9999);
    <style>
        .lzb-gutenberg-image {
            .lzb-gutenberg-
            .lzb-gutenberg-image-item img { display:none }
        }
        #<?= $blockId ?> {
            /* Sizes <?= implode(\',\', array_keys($attributes[\'image\'][\'sizes\'])) ?> */
            overflow:clip;
            <?php if($attributes[\'has-frame\']) { ?>
                box-shadow:
            		0px 0px 0 4px <?= $attributes[\'frame-color\'] ?>,
            		4px -2px 0 4px <?= $attributes[\'frame-color\'] ?>;
            <?php } ?>
            background-image: url(<?= $attributes[\'image\'][\'sizes\'][ $attributes[\'resolution\'] ][\'url\'] ?>);
            background-repeat: no-repeat;
            background-size: <?= $attributes[\'initial-zoom\'] ?>%;
            <?= $attributes[\'rounding-radius\'] ? "border-radius:{$attributes[\'rounding-radius\']}px;" : \'\' ?>
            <?= $attributes[\'width\'] ? "width: {$attributes[\'width\']}rem;" : \'\' ?>
            <?= $attributes[\'height\'] ? "height: {$attributes[\'height\']}rem;" : \'\' ?>
            <?= $attributes[\'position-vertical\'] || $attributes[\'position-horizontal\'] ? "background-position: {$attributes[\'position-horizontal\']}% {$attributes[\'position-vertical\']}%;" : \'\' ?>
            transition: .5s ease;
        }
        <?php if($attributes[\'zoom-on-hover\']) { ?>
            #<?= $blockId ?>:hover { background-size: <?= $attributes[\'initial-zoom\'] + 5 ?>%    }
        <?php } ?>
    </style>
        <figure id="<?= $blockId ?>" class="<?= $attributes[\'has-frame\'] ? \'img-blueframe\' : \'\' ?>">
            <span class="screen-reader-text"><?= addslashes($attributes[\'image\'][\'alt\']) ?></span>
        </figure>',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    if($attributes[\'image\']) {
        $blockId = \'jsImage\'. rand(1000,9999);
        if($attributes[\'link\']) { ?>
            <a href="<?= $attributes[\'link\'] ?>">
        <?php } ?>
    <style>
        #<?= $blockId ?> {
            /* Sizes <?= implode(\',\', array_keys($attributes[\'image\'][\'sizes\'])) ?> */
            overflow:clip;
            <?php if($attributes[\'has-frame\']) { ?>
                box-shadow:
            		0px 0px 0 4px <?= $attributes[\'frame-color\'] ?>,
            		4px -2px 0 4px <?= $attributes[\'frame-color\'] ?>;
            <?php } ?>
            background-image: url(<?= $attributes[\'image\'][\'sizes\'][ $attributes[\'resolution\'] ][\'url\'] ?>);
            background-repeat: no-repeat;
            background-size: <?= $attributes[\'initial-zoom\'] ?>%;
            <?= $attributes[\'rounding-radius\'] ? "border-radius:{$attributes[\'rounding-radius\']}px;" : \'\' ?>
            <?= $attributes[\'width\'] ? "width: {$attributes[\'width\']}rem;" : \'\' ?>
            <?= $attributes[\'height\'] ? "height: {$attributes[\'height\']}rem;" : \'\' ?>
            <?= $attributes[\'position-vertical\'] || $attributes[\'position-horizontal\'] ? "background-position: {$attributes[\'position-horizontal\']}% {$attributes[\'position-vertical\']}%;" : \'\' ?>
            transition: .5s ease;
        }
        <?php if($attributes[\'zoom-on-hover\']) { ?>
            #<?= $blockId ?>:hover { background-size: <?= $attributes[\'initial-zoom\'] + 5 ?>%    }
        <?php } ?>
    </style>
        <figure id="<?= $blockId ?>" class="<?= $attributes[\'has-frame\'] ? \'img-blueframe\' : \'\' ?>">
            <span class="screen-reader-text"><?= addslashes($attributes[\'image\'][\'alt\']) ?></span>
        </figure>
    <?php
        echo $attributes[\'link\'] ? \'</a>\' : \'\';
    } ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 80482,
        'title' => 'People List by Categories',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 13c-1.2 0-3.07.34-4.5 1-1.43-.67-3.3-1-4.5-1C5.33 13 1 14.08 1 16.25V19h22v-2.75c0-2.17-4.33-3.25-6.5-3.25zm-4 4.5h-10v-1.25c0-.54 2.56-1.75 5-1.75s5 1.21 5 1.75v1.25zm9 0H14v-1.25c0-.46-.2-.86-.52-1.22.88-.3 1.96-.53 3.02-.53 2.44 0 5 1.21 5 1.75v1.25zM7.5 12c1.93 0 3.5-1.57 3.5-3.5S9.43 5 7.5 5 4 6.57 4 8.5 5.57 12 7.5 12zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 5.5c1.93 0 3.5-1.57 3.5-3.5S18.43 5 16.5 5 13 6.57 13 8.5s1.57 3.5 3.5 3.5zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/people-list-by-category',
        'description' => '',
        'category' => 'pods',
        'category_label' => 'pods',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_3a780d4c42' => array(
                'type' => 'select',
                'name' => 'categories',
                'default' => 'Staff',
                'label' => 'Categories',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Board Member',
                        'value' => 'Board Member',
                    ),
                    array(
                        'label' => 'Diversity, Equity, and Inclusion Council',
                        'value' => 'Diversity, Equity, and Inclusion Council',
                    ),
                    array(
                        'label' => 'Executive Team',
                        'value' => 'Executive Team',
                    ),
                    array(
                        'label' => 'External',
                        'value' => 'External',
                    ),
                    array(
                        'label' => 'Leadership Team',
                        'value' => 'Leadership Team',
                    ),
                    array(
                        'label' => 'Literacy Champion',
                        'value' => 'Literacy Champion',
                    ),
                    array(
                        'label' => 'National Early Education Council',
                        'value' => 'National Early Education Council',
                    ),
                    array(
                        'label' => 'Staff',
                        'value' => 'Staff',
                    ),
                    array(
                        'label' => 'Executive Team',
                        'value' => 'Executive Team',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'true',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $divId = \'people-list\'.rand(1000,9999); ?>
    <style>
        .lzb-preview-server .pods-list-people {display: block}
    </style>
    <div id="<?= $divId ?>" class="pods-list-container pods-list-people">
    <?php
    if(!isset($attributes[\'categories\']))
        return;
    $categories = [];
    foreach($attributes[\'categories\'] as $c) {
    	$categories[] = "position like \'%{$c}%\'";
    }
    $people = pods(\'person\')->find(array(\'where\'=>\'active = true and (\'.implode(\' or \', $categories).\')\', \'orderby\' => \'sort_order DESC, last_name ASC\'), 200);
    if($people->total == 0)
        return;
    while($people->fetch()) {
    	$link = \'/people/\'.$people->display(\'post_name\');
    	echo <<<EOD
    		<div class="pods-list-item people-list-item" data-filter-on="{$people->display(\'position\')}">
    			<a href="/people/{$people->display(\'post_name\')}">
    				<div class="img-hoverzoom"><figure style="background-image:url({$people->display(\'post_thumbnail_url.medium_large\')})"></figure></div>
    				{$people->display(\'first_name\')} {$people->display(\'last_name\')},<br/>
    				{$people->display(\'role_title\')}
    			</a>
    		</div>
    EOD;
    	}
    ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 80027,
        'title' => 'Site Section Theme Elements',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.66 7.93L12 2.27 6.34 7.93c-3.12 3.12-3.12 8.19 0 11.31C7.9 20.8 9.95 21.58 12 21.58c2.05 0 4.1-.78 5.66-2.34 3.12-3.12 3.12-8.19 0-11.31zM12 19.59c-1.6 0-3.11-.62-4.24-1.76C6.62 16.69 6 15.19 6 13.59s.62-3.11 1.76-4.24L12 5.1v14.49z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/site-section-theme-elements',
        'description' => '',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_858bf949d0' => array(
                'type' => 'radio',
                'name' => 'element',
                'default' => 'sidebar',
                'label' => 'Element',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Sidebar',
                        'value' => 'sidebar',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c81ba74690' => array(
                'type' => 'toggle',
                'name' => 'rounded',
                'default' => '',
                'label' => 'Rounded',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => 'Show rounded edges or square?',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $hasBackground = false;
    foreach(get_the_category() as $c) {
    	switch($c->slug) {
    		case \'site-section-program\':
    		    $hasBackground = 2;
    			break(2);
    		case \'site-section-organization\':
    		    $hasBackground = 0;
    		    break(2);
    		case \'site-section-advocacy\':
    		    $hasBackground = 1;
    		    break(2);
    		case \'site-section-read-for-the-record\':
    		    $hasBackground = 3;
    		    break(2);
    	}
    }
    $hasBackground = 8; // testing out setting all site sections to same color
    ?>
    <div class="<?= $attributes[\'element\'] == \'sidebar\' ? \'padding-1rem has-ast-global-color-\'.$hasBackground.\'-background-color\' : \'\'?>" style="<?= $attributes[\'rounded\'] ? \'border-radius:24px;\' : \'\'?>">
        <InnerBlocks />
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 80008,
        'title' => 'Page Detail',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 14l3 3v5h6v-5l3-3V9H6zm5-12h2v3h-2zM3.5 5.875L4.914 4.46l2.12 2.122L5.62 7.997zm13.46.71l2.123-2.12 1.414 1.414L18.375 8z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/page-detail',
        'description' => '',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_4b9a5b4995' => array(
                'type' => 'radio',
                'name' => 'detail',
                'default' => 'title',
                'label' => 'Detail',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Title',
                        'value' => 'title',
                    ),
                    array(
                        'label' => 'Featured Image',
                        'value' => 'image',
                    ),
                    array(
                        'label' => 'Excerpt',
                        'value' => 'excerpt',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $output = \'\';
    switch($attributes[\'detail\']) {
        case \'title\': $output = \'<h1>\'.do_shortcode(get_the_title()).\'</h1>\'; break;
        case \'image\': $output = get_the_post_thumbnail(null, \'medium_large\'); break;
        case \'excerpt\': $output = \'<div class="header_excerpt">\'.do_shortcode(wp_trim_words(get_the_excerpt(), 45, \'…\')).\'</div>\'; break;
    }
    if($attributes[\'detail\'] == \'image\' && in_array(get_post_type(), [\'site\',\'person\'])) {
        // maybe do random image here?
        $output = \'\';
    }
    echo $output;
    ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79857,
        'title' => 'Content List by Page Category',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.11-.9-2-2-2H4c-1.11 0-2 .89-2 2v10c0 1.1.89 2 2 2H0v2h24v-2h-4zm-7-3.53v-2.19c-2.78 0-4.61.85-6 2.72.56-2.67 2.11-5.33 6-5.87V7l4 3.73-4 3.74z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/content-list-by-page-category',
        'description' => '',
        'category' => 'embed',
        'category_label' => 'embed',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_251b4045f1' => array(
                'type' => 'text',
                'name' => 'label',
                'default' => 'Shareables',
                'label' => 'Label',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '255',
            ),
            'control_ccc9924bf7' => array(
                'type' => 'toggle',
                'name' => 'shareable-only',
                'default' => '',
                'label' => 'Shareable Only',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $divId = \'resource-list\'.rand(1000,9999);
    $contentCategories = false;
    foreach(get_the_category() as $c) {
    	switch($c->slug) {
    		case \'site-section-program\':
    			$contentCategories = array(\'Activity Guide\',\'Classroom\',\'Program Operations\'); break(2);
    		case \'site-section-organization\':
    			$contentCategories = array(\'Career Resource\',\'Workforce\'); break(2);
    		case \'site-section-advocacy\':
    			$contentCategories = array(\'Policy\'); break(2);
    	}
    }
    ?>
    <div id="<?= $divId ?> pods-list-content">
    <?php
    if(!$contentCategories)
        return;
    $categoryMatches = join("%\' or content_categories like \'%",$contentCategories);
    $shareable = $attributes[\'shareable-only\'] ? "content_categories like \'%Shareable%\' and" : \'\';
    $contents = pods(\'content\')->find(array(\'where\'=>$shareable." (content_categories like \'%".$categoryMatches."\')"));
    if($contents->total_found() == 0)
        return;
    $label = $attributes[\'label\'] ? \'<h4 class="wp-block-heading">\'.$attributes[\'label\'].\'</h4>\' : \'\';
    echo $label.\'	<ul>\';
    while($contents->fetch()) {
    	$link = \'/contents/\'.$contents->display(\'post_name\');
    	$title = $contents->display(\'post_title\');
    	echo <<<EOD
    	<li><a class="pods-single-content" href="$link">$title</a></li>
    EOD;
    	}
    echo \'	</ul>\';
    ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79737,
        'title' => 'Page Table of Contents',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 21h18v-2H3v2zM3 8v8l4-4-4-4zm8 9h10v-2H11v2zM3 3v2h18V3H3zm8 6h10V7H11v2zm0 4h10v-2H11v2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/page-table-of-contents',
        'description' => '',
        'category' => 'widgets',
        'category_label' => 'widgets',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_569ab343be' => array(
                'type' => 'text',
                'name' => 'title',
                'default' => 'This Page',
                'label' => 'Title',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_7a3b0f446a' => array(
                'type' => 'number',
                'name' => 'header-depth',
                'default' => '1',
                'label' => 'Header Depth',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '5',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_f7da134ab4' => array(
                'type' => 'toggle',
                'name' => 'skip-first-header',
                'default' => '',
                'label' => 'Skip First Header',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_3a8b96411c' => array(
                'type' => 'toggle',
                'name' => 'expandable',
                'default' => '',
                'label' => 'Expandable',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $widgetId = \'toc\'.rand(100,999); ?>
    <ul id="<?= $widgetId ?>" class="pageTableOfContents"></ul>
    <style>
        #<?= $widgetId ?> {
            /*display:flex;*/
            list-style: none;
            text-align: center;
            li { display:inline-block; }
            li:after { content: "|"; padding: 0.5rem; text-align:center; }
            li:last-child:after { content: ""; }
        }
    </style>
    <script>
    const <?= $widgetId ?>_slugify = t=>{return t.toLowerCase().replace(/[^\\w\\d]+/g,\'-\').trim() }
    const <?= $widgetId ?>_getPageContentHeaders = (level, total, base)=>{
        let output = {}
        if(!base)
            base = document.getElementById(\'primary\')
        else
            base = base.parentElement
        const headers = base.querySelectorAll(\'h\'+ level)
        const list = <?= $attributes[\'skip-first-header\'] ? \'true\' : \'false\' ?> && level == 2 ? Array.from(headers).slice(1) : Array.from(headers)
        list.forEach(h=>{
            h.id = <?= $widgetId ?>_slugify(h.innerText)
            output[h.innerText] = null
            if(level != total)
                output[h.innerText] = <?= $widgetId ?>_getPageContentHeaders(level+1, total, h)
        })
        return output
    }
    const <?= $widgetId ?>_formatTOC = (items, label)=>{
        let output = \'\'
        if(items == null)
            return \'<li class="<?= $widgetId ?>"><a href="#\'+ <?= $widgetId ?>_slugify(label) + \'">\' + label + \'</a></li>\'
    // should deactivate multi-level since they don\'t seem to want to use it that way, but just in case
        if(label)
            output = \'<li class="<?= $widgetId ?>"><a href="#\'+ <?= $widgetId ?>_slugify(label) + \'">\' + label + \'</a><ul>\'
        Object.keys(items).forEach(i=>output += <?= $widgetId ?>_formatTOC(items[i], i))
        if(label)
            output += \'</ul></li>\'
        return output
    }
    addEventListener("DOMContentLoaded", (event) => {
        // always skip h1 elements
        const toc = <?= $widgetId ?>_getPageContentHeaders(2, <?= $attributes[\'header-depth\'] ?> + 1)
        if(Object.keys(toc).length) {
            const output = <?= $widgetId ?>_formatTOC(toc)
            if(output) {
                const target = document.getElementById(\'<?= $widgetId ?>\')
                const template = document.createElement(\'template\')
                template.innerHTML = output
                Array.from(template.content.children).forEach(c=>target.append(c))
            }
        }
    });
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79698,
        'title' => 'Job List by Page Category',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/job-list-by-page-category',
        'description' => '',
        'category' => 'pods',
        'category_label' => 'pods',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_251b4045f1' => array(
                'type' => 'text',
                'name' => 'label',
                'default' => 'Careers',
                'label' => 'Label',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '255',
            ),
            'control_b1a8a7402d' => array(
                'type' => 'number',
                'name' => 'limit',
                'default' => '',
                'label' => 'Limit',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '200',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $divId = \'job-list\'.rand(1000,9999);
    $jobCategories = false;
    foreach(get_the_category() as $c) {
    	switch($c->slug) {
    		case \'site-section-program\':
        		$jobCategories = array(\'Education\',\'Research and Evaluation\',\'Program Management\',\'Site Management\',\'Program Operations\'); break(2);
    		case \'site-section-organization\':
            	$jobCategories = array(\'Development\',\'Alumni Relations\',\'Finance\',\'Operations and Technology\',\'Talent Management\'); break(2);
    		case \'site-section-advocacy\':
        		$jobCategories = array(\'FAOSchwarz Fellowship\',\'Policy and Government Relations\'); break(2);
    	}
    }
    ?>
    <div id="<?= $divId ?> pods-list-job">
    <?php
    if($jobCategories)
        $jobs = pods(\'job\')->find(array(\'where\'=>"active = true and job_category in (\'".join("\',\'",$jobCategories)."\')",
            \'limit\'=>$attributes[\'limit\'],
            \'orderby\'=>\'job_featured DESC, job_salary_lower_range DESC\'));
    // if no jobCategories then just list featured
    if(!$jobCategories || $jobs->total == 0)
        $jobs = pods(\'job\')->find(array(\'where\'=>"active = true and job_featured = 1", \'limit\'=>$attributes[\'limit\'],
            \'orderby\'=>\'job_salary_lower_range DESC\'));
    echo ($attributes[\'label\'] ? \'<h4 class="wp-block-heading"><a href="/jobs">\'.$attributes[\'label\'].\'</a></h4>\' : \'\').\'	<ul>\';
    while($jobs->fetch()) {
    	$link = \'/jobs/\'.$jobs->display(\'post_name\');
    	$title = $jobs->display(\'post_title\');
    	echo <<<EOD
    	<li><a class="pods-single-job" href="$link">$title</a></li>
    EOD;
    	}
    echo \'	</ul>\';
    ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79652,
        'title' => 'Post List from Page Categories',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 9H2v2h17V9zm0-4H2v2h17V5zM2 15h13v-2H2v2zm15-2v6l5-3-5-3z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/post-list-from-page-categories',
        'description' => '',
        'category' => 'text',
        'category_label' => 'text',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_2dc8024b59' => array(
                'type' => 'text',
                'name' => 'label',
                'default' => 'Recent News',
                'label' => 'Label',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '255',
            ),
            'control_94f9d3418f' => array(
                'type' => 'number',
                'name' => 'post-count',
                'default' => '5',
                'label' => 'Post Count',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '50',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_b8cae44c3d' => array(
                'type' => 'select',
                'name' => 'title-format',
                'default' => '3',
                'label' => 'Title Format',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'H2',
                        'value' => 'h2',
                    ),
                    array(
                        'label' => 'H3',
                        'value' => 'h3',
                    ),
                    array(
                        'label' => 'H4',
                        'value' => 'h4',
                    ),
                    array(
                        'label' => 'H5',
                        'value' => 'h5',
                    ),
                    array(
                        'label' => 'Plain',
                        'value' => 'div',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_fa7be5482e' => array(
                'type' => 'number',
                'name' => 'preview-length',
                'default' => '100',
                'label' => 'Preview Length',
                'help' => 'The number of characters to preview from if there is no excerpt',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_8f790c41bd' => array(
                'type' => 'toggle',
                'name' => 'use-featured-image',
                'default' => '',
                'label' => 'Use Featured Image',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_218af04ffe' => array(
                'type' => 'toggle',
                'name' => 'show-publication-date',
                'default' => '',
                'label' => 'Show Publication Date',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    // need to add order/sorting
    $categoryIDs = array();
    $categories = get_the_category();
    foreach($categories as $c) {
        $categoryIDs[] = $c->id;
    }
    $itemCategory = get_category_by_slug(get_post_field(\'post_name\', get_post()));
    if(!empty($itemCategory))
        $categoryIDs[] = $itemCategory->cat_ID;
    $postType = get_post_field(\'post_type\', get_post());
    $postTypeCategory = false;
    switch($postType) {
        case \'state\': $postTypeCategory = get_category_by_slug(\'all-states\'); break;
        case \'site\': $postTypeCategory = get_category_by_slug(\'all-sites\'); break;
    }
    if($postTypeCategory)
        $categoryIDs[] = $postTypeCategory->cat_ID;
    if(empty($categoryIDs))
        return;
    $posts = get_posts(array(\'category\' => implode(\',\', $categoryIDs), \'post_type\' => \'post\', \'post_status\' => \'publish\', \'numberposts\' => $attributes[\'post-count\']));
    $label = $attributes[\'label\'] ? \'<h4 class="wp-block-heading"><a href="/news">\'.$attributes[\'label\'].\'</a></h4>\' : \'\';
    echo <<<EOD
        $label
        <ul class="post-category-list">
    EOD;
    foreach($posts as $post) {
        $featuredImage = \'\';
        if($attributes[\'use-featured-image\'] && has_post_thumbnail($post->ID))
            $featuredImage = \'<div><a href="/news/\'.$post->post_name.\'">\'.get_the_post_thumbnail($post->ID, \'medium\').\'</a></div>\';
        $title = $post->post_title;
        $output = <<<POST
        <li class="post-category-item">
            $featuredImage
            <${attributes[\'title-format\']}>
                    <a href="/news/{$post->post_name}">{$title}</a>
    POST;
            if($attributes[\'show-publication-date\'])
                $output .= "<span>{$post->post_date}</span>";
            $output .=    "     </${attributes[\'title-format\']}>";
            $content = wp_strip_all_tags(empty($post->post_excerpt) ? $post->post_content : $post->post_excerpt);
            $output .= $attributes[\'preview-length\'] ? trim(strlen($content) > $attributes[\'preview-length\'] ? substr($content, 0, $attributes[\'preview-length\']) : $content).\'...\' : \'\';
        $output .= \'</li>\';
        echo $output;
    }
    echo \'</ul>\';
    ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79474,
        'title' => 'Overlap Style Element',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M2.53 19.65l1.34.56v-9.03l-2.43 5.86c-.41 1.02.08 2.19 1.09 2.61zm19.5-3.7L17.07 3.98c-.31-.75-1.04-1.21-1.81-1.23-.26 0-.53.04-.79.15L7.1 5.95c-.75.31-1.21 1.03-1.23 1.8-.01.27.04.54.15.8l4.96 11.97c.31.76 1.05 1.22 1.83 1.23.26 0 .52-.05.77-.15l7.36-3.05c1.02-.42 1.51-1.59 1.09-2.6zM7.88 8.75c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-2 11c0 1.1.9 2 2 2h1.45l-3.45-8.34v6.34z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/overlap-style-element',
        'description' => '',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => true,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_7b88164e9f' => array(
                'type' => 'image',
                'name' => 'element',
                'default' => '',
                'label' => 'Element',
                'help' => 'IMPORTANT: Be sure to select an SVG file if you want to recolor the style element.',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'insert_from_url' => 'false',
                'preview_size' => 'medium',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_53985b4a6b' => array(
                'type' => 'radio',
                'name' => 'front-or-back',
                'default' => 'front',
                'label' => 'Front or Back',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Front',
                        'value' => 'front',
                    ),
                    array(
                        'label' => 'Back',
                        'value' => 'back',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_5b98f84fe3' => array(
                'type' => 'range',
                'name' => 'element-transparency',
                'default' => '1',
                'label' => 'Element Transparency',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '1',
                'step' => '0.05',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c9099b4330' => array(
                'type' => 'color',
                'name' => 'color',
                'default' => '',
                'label' => 'Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6769ab48e2' => array(
                'type' => 'range',
                'name' => 'filter-strength',
                'default' => '0.5',
                'label' => 'Filter Strength',
                'help' => 'Use this to tune the color shading when using a plain image',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '1',
                'step' => '0.1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_98eb0b412d' => array(
                'type' => 'number',
                'name' => 'width',
                'default' => '10',
                'label' => 'Width',
                'help' => 'in REM, set to 0 for automatic sizing',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_5b8bc746ea' => array(
                'type' => 'number',
                'name' => 'height',
                'default' => '0',
                'label' => 'Height',
                'help' => 'in REM, set to 0 for automatic sizing',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_076b3c4b59' => array(
                'type' => 'number',
                'name' => 'size-scale',
                'default' => '1',
                'label' => 'Size Scale',
                'help' => 'Alternative way to size the style element, useful for gradients',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '0.1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_bfaab24fe2' => array(
                'type' => 'radio',
                'name' => 'alignment',
                'default' => 'left',
                'label' => 'Alignment',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Center',
                        'value' => 'center',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c7ea904afa' => array(
                'type' => 'number',
                'name' => 'vertical-adjust',
                'default' => '0',
                'label' => 'Vertical Adjust',
                'help' => 'In pixels, can be negative',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c9680644e2' => array(
                'type' => 'number',
                'name' => 'horizontal-adjust',
                'default' => '0',
                'label' => 'Horizontal Adjust',
                'help' => 'in pixels, can be negative',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_4149074c6d' => array(
                'type' => 'range',
                'name' => 'rotation',
                'default' => '0',
                'label' => 'Rotation',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '360',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '<?php
    $blockId = \'figure\'.rand(100,999);
    if($attributes[\'element\']) {
        $output = \'\';
        if(str_contains(strtolower($attributes[\'element\'][\'url\']),\'.svg\')) {
            try {
                $img = curl_init();
                curl_setopt($img, CURLOPT_URL, $attributes[\'element\'][\'url\']);
                curl_setopt($img, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($img);
                curl_close($img);
            } catch(Exception $e) {
                $output = \'That image was not found.\';
            }
        } else {
            $output = \'<div class="color-wrapper"><img src="\'.$attributes[\'element\'][\'url\'].\'" alt="\'.$attributes[\'element\'][\'alt\'].\'" /></div>\';
        }
    }
    ?>
    <style>
        #<?= $blockId ?> {
            position: relative;
            z-index: 0;
            .style-element {
                position: <?= $attributes[\'front-or-back\'] == \'front\' ? \'relative\' : \'absolute\' ?>;
                <?= $attributes[\'front-or-back\'] == \'front\' ? \'\' : \'z-index: -1;\' ?>
                <?= $attributes[\'vertical-adjust\'] ? \'top: \'.$attributes[\'vertical-adjust\'].\'px;\' : \'\' ?>
            <?php // layout
                $align = \'\';
                $transforms = [\'scale(\'.$attributes[\'size-scale\'].\')\'];
                if(isset($attributes[\'rotation\']))
                    $transforms[] = \'rotate(\'.$attributes[\'rotation\'].\'deg)\';
                if($attributes[\'alignment\'] == \'center\') {
                    $align = \'left: 50%\';
                    $transforms[] = \'translateX(calc(-50% / \'.$attributes[\'size-scale\'].\'))\';
                } elseif($attributes[\'alignment\'] == \'right\') {
                    $align = \'right: \'.$attributes[\'horizontal-adjust\'].\'px\';
                } else {
                    $align = \'left: \'.$attributes[\'horizontal-adjust\'].\'px\';
                }
            ?>
                <?= $align ?>;
                transform: <?= implode(\' \', $transforms) ?>;
                opacity: <?= $attributes[\'element-transparency\'] ?>;
                <?= $attributes[\'width\'] > 0 ? \'width: \'.$attributes[\'width\'].\'rem;\' : \'\' ?>
                <?= $attributes[\'height\'] > 0 ? \'height: \'.$attributes[\'height\'].\'rem;\' : \'\' ?>
                img {filter: opacity(<?= 1 - $attributes[\'filter-strength\'] ?>)}
                svg {
                    transform: scale(100%, calc(<?= $attributes[\'height\'] > 0 ? \'300% * \'.$attributes[\'height\'].\'/\'.$attributes[\'width\'] : \'100%\' ?>));
                    path { <?= $attributes[\'color\'] ? \'fill: \'.$attributes[\'color\'].\' !important;\' : \'\' ?> }
                    .gradient-start { stop-color:<?= $attributes[\'color\'] ?> }
                    .gradient-stop { stop-color: #fff0 }
                    .color-wrapper { background: <?= $attributes[\'color\'] ?>; }
                }
            }
        }
    </style>
    <div id="<?= $blockId ?>">
        <?php if($attributes[\'front-or-back\'] == \'front\') { ?>
            <small>Will be shown overlapped on the front end</small>
            <InnerBlocks />
        <?php } ?>
        <figure class="style-element">
            <?= $output ?>
            <figcaption class="for-screenreaders-only"><?= $attributes[\'element\'][\'alt\'] ?></figcaption>
        </figure>
        <?php if($attributes[\'front-or-back\'] == \'back\') { ?>
            <InnerBlocks />
        <?php } ?>
    </div>',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $blockId = \'figure\'.rand(100,999);
    if($attributes[\'element\']) {
        $output = \'\';
        if(str_contains(strtolower($attributes[\'element\'][\'url\']),\'.svg\')) {
            try {
                $img = curl_init();
                curl_setopt($img, CURLOPT_URL, $attributes[\'element\'][\'url\']);
                curl_setopt($img, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($img);
                $output = stristr($output,\'<svg\');
                curl_close($img);
            } catch(Exception $e) {
                $output = \'That image was not found.\';
            }
        } else {
            $output = \'<div class="color-wrapper"><img src="\'.$attributes[\'element\'][\'url\'].\'" alt="\'.$attributes[\'element\'][\'alt\'].\'" /></div>\';
        }
    }
    ?>
    <style>
        #<?= $blockId ?> {
            position: relative;
            z-index:0;
            .style-element {
                position: absolute;
                <?= $attributes[\'front-or-back\'] == \'front\' ? \'\' : \'z-index: -1;\' ?>
                top: <?= $attributes[\'vertical-adjust\'] ? $attributes[\'vertical-adjust\'].\'px;\' : \'0;\' ?>
            <?php // layout
                $align = \'\';
                $transforms = [\'scale(\'.$attributes[\'size-scale\'].\')\'];
                if(isset($attributes[\'rotation\']))
                    $transforms[] = \'rotate(\'.$attributes[\'rotation\'].\'deg)\';
                if($attributes[\'alignment\'] == \'center\') {
                    $align = \'left: 50%\';
                    $transforms[] = \'translateX(calc(-50% / \'.$attributes[\'size-scale\'].\'))\';
                } elseif($attributes[\'alignment\'] == \'right\') {
                    $align = \'right: \'.$attributes[\'horizontal-adjust\'].\'px\';
                } else {
                    $align = \'left: \'.$attributes[\'horizontal-adjust\'].\'px\';
                }
            ?>
                <?= $align ?>;
                transform: <?= implode(\' \', $transforms) ?>;
                opacity: <?= $attributes[\'element-transparency\'] ?>;
                <?= $attributes[\'width\'] > 0 ? \'width: \'.$attributes[\'width\'].\'rem;\' : \'\' ?>
                <?= $attributes[\'height\'] > 0 ? \'height: \'.$attributes[\'height\'].\'rem;\' : \'\' ?>
                img {filter: opacity(<?= 1 - $attributes[\'filter-strength\'] ?>)}
                svg {
                    transform: scale(100%, calc(<?= $attributes[\'height\'] > 0 ? \'300% * \'.$attributes[\'height\'].\'/\'.$attributes[\'width\'] : \'100%\' ?>));
                    path { <?= $attributes[\'color\'] ? \'fill: \'.$attributes[\'color\'].\' !important;\' : \'\' ?> }
                    .gradient-start { stop-color:<?= $attributes[\'color\'] ?> }
                    .gradient-stop { stop-color: #fff0 }
                    .color-wrapper { background: <?= $attributes[\'color\'] ?>; }
                }
            }
        }
        </style>
    <div id="<?= $blockId ?>">
        <?php if($attributes[\'front-or-back\'] == \'front\') { ?>
            <InnerBlocks />
        <?php } ?>
        <figure class="style-element">
            <?= $output ?>
            <figcaption class="for-screenreaders-only"><?= $attributes[\'element\'][\'alt\'] ?></figcaption>
        </figure>
        <?php if($attributes[\'front-or-back\'] == \'back\') { ?>
            <InnerBlocks />
        <?php } ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79308,
        'title' => 'Animated Counter',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M7.88 3.39L6.6 1.86 2 5.71l1.29 1.53 4.59-3.85zM22 5.72l-4.6-3.86-1.29 1.53 4.6 3.86L22 5.72zM12 4c-4.97 0-9 4.03-9 9s4.02 9 9 9c4.97 0 9-4.03 9-9s-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7zm1-11h-2v3H8v2h3v3h2v-3h3v-2h-3V9z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/animated-counter',
        'description' => '',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_01d9c648ec' => array(
                'type' => 'number',
                'name' => 'start',
                'default' => '0',
                'label' => 'Start',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '',
                'step' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_2418ba4ccc' => array(
                'type' => 'number',
                'name' => 'end',
                'default' => '',
                'label' => 'End',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '',
                'step' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_8269894fa5' => array(
                'type' => 'number',
                'name' => 'duration',
                'default' => '1000',
                'label' => 'Duration in Milliseconds',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '10',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_f9296b4389' => array(
                'type' => 'select',
                'name' => 'format',
                'default' => 'currency',
                'label' => 'Format',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Currency',
                        'value' => 'currency',
                    ),
                    array(
                        'label' => 'Commas',
                        'value' => 'commas',
                    ),
                    array(
                        'label' => 'Plain',
                        'value' => 'plain',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_459ab34183' => array(
                'type' => 'number',
                'name' => 'font-size',
                'default' => '1',
                'label' => 'Font Size',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '',
                'step' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_34ea4b4e3e' => array(
                'type' => 'select',
                'name' => 'font-size-units',
                'default' => 'rem',
                'label' => 'Font Size Units',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Pixel',
                        'value' => 'px',
                    ),
                    array(
                        'label' => 'REM',
                        'value' => 'rem',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_5fcab74189' => array(
                'type' => 'color',
                'name' => 'color',
                'default' => '',
                'label' => 'Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_e4a8a64272' => array(
                'type' => 'select',
                'name' => 'font-family',
                'default' => 'heading',
                'label' => 'Font Family',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Heading font',
                        'value' => 'heading',
                    ),
                    array(
                        'label' => 'Body font',
                        'value' => 'body',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_9d384e4934' => array(
                'type' => 'radio',
                'name' => 'alignment',
                'default' => 'left',
                'label' => 'Alignment',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Center',
                        'value' => 'center',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_19991c4ff9' => array(
                'type' => 'number',
                'name' => 'delay',
                'default' => '0',
                'label' => 'Delay in Milliseconds',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    	$widgetId = \'Widget\'.rand(100,999);
    ?>
    <figure id="<?= $widgetId ?>" style="text-align:<?= $attributes[\'alignment\'] ?>;font-family:var(--font-theme-<?= $attributes[\'font-family\'] ?>);color:<?= $attributes[\'color\'] ?>;font-size: <?= $attributes[\'font-size\'] ?><?= $attributes[\'font-size-units\'] ?>;"></figure>
    <script>
    if(document.documentElement.style.getPropertyValue(\'--font-theme-heading\') === \'\') {
    	let testHeading = document.createElement(\'h1\')
    	let testBody = document.createElement(\'p\')
    	document.body.appendChild(testHeading)
    	document.body.appendChild(testBody)
    	document.documentElement.style.setProperty(\'--font-theme-heading\', window.getComputedStyle(testHeading, null).getPropertyValue(\'font-family\'))
    	document.documentElement.style.setProperty(\'--font-theme-body\', window.getComputedStyle(testBody, null).getPropertyValue(\'font-family\'))
    }
    const callback<?= $widgetId ?> = (e, o) => {
    	setTimeout((entries,observer)=>{
    		entries.forEach((e) => {
    			let startTimestamp = null
    			const step = (timestamp) => {
    				if (!startTimestamp) startTimestamp = timestamp
    				const progress = Math.min((timestamp - startTimestamp) / <?= $attributes[\'duration\'] ?>, 1)
    				newValue = Math.floor(progress * (<?= $attributes[\'end\'] ?> - <?= $attributes[\'start\'] ?>) + <?= $attributes[\'start\'] ?>)
    				e.target.innerHTML = (new Intl.NumberFormat(\'en-US\',
    					(\'<?= $attributes[\'format\'] ?>\' === \'currency\' ? {style:"currency", currency:"USD"} : {})
    				)).format(newValue)
    				if (progress < 1) {
    					window.requestAnimationFrame(step)
    				}
    			}
    			window.requestAnimationFrame(step)
    			observer.unobserve(e.target)
    		})
    	}, <?= $attributes[\'delay\'] ? $attributes[\'delay\'] : \'0\' ?>, e, o)
    }
    const observer<?= $widgetId ?> = new IntersectionObserver(callback<?= $widgetId ?>)
    observer<?= $widgetId ?>.observe(document.getElementById("<?= $widgetId ?>"))
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79240,
        'title' => 'Style Element',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M18 4V3c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h12c.55 0 1-.45 1-1V6h1v4H9v11c0 .55.45 1 1 1h2c.55 0 1-.45 1-1v-9h8V4h-3z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/style-element',
        'description' => '',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => true,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_7b88164e9f' => array(
                'type' => 'image',
                'name' => 'element',
                'default' => '',
                'label' => 'Element',
                'help' => 'IMPORTANT: Be sure to select an SVG file if you want to recolor the style element.',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'insert_from_url' => 'false',
                'preview_size' => 'medium',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_c9099b4330' => array(
                'type' => 'color',
                'name' => 'color',
                'default' => '',
                'label' => 'Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6769ab48e2' => array(
                'type' => 'range',
                'name' => 'filter-strength',
                'default' => '0.5',
                'label' => 'Filter Strength',
                'help' => 'Use this to tune the color shading when using a plain image',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '1',
                'step' => '0.1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_f269cc42f3' => array(
                'type' => 'number',
                'name' => 'width',
                'default' => '10',
                'label' => 'Width',
                'help' => 'in REM, set to 0 for automatic sizing',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_40092a43b4' => array(
                'type' => 'number',
                'name' => 'height',
                'default' => '0',
                'label' => 'Height',
                'help' => 'in REM, set to 0 for automatic sizing',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_1df8be4f70' => array(
                'type' => 'range',
                'name' => 'rotation',
                'default' => '0',
                'label' => 'Rotation',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '360',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $blockId = \'figure\'.rand(100,999);
    if($attributes[\'element\']) {
        $output = \'\';
        if(str_contains(strtolower($attributes[\'element\'][\'url\']),\'.svg\')) {
            try {
                $img = curl_init();
                curl_setopt($img, CURLOPT_URL, $attributes[\'element\'][\'url\']);
                curl_setopt($img, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($img);
                curl_close($img);
            } catch(Exception $e) {
                $output = \'That image was not found.\';
            }
        } else {
            $output = \'<div class="color-wrapper"><img src="\'.$attributes[\'element\'][\'url\'].\'" alt="\'.$attributes[\'element\'][\'alt\'].\'" /></div>\';
        }
    }
    ?>
    <style>
        #<?= $blockId ?> {
            <?= isset($attributes[\'rotation\']) ? \'transform: rotate(\'.$attributes[\'rotation\'].\'deg);\' : \'\' ?>
            <?= $attributes[\'width\'] > 0 ? \'width: \'.$attributes[\'width\'].\'rem;\' : \'\' ?>
            <?= $attributes[\'height\'] > 0 ? \'height: \'.$attributes[\'height\'].\'rem;\' : \'\' ?>
            svg {
                transform: scale(100%, calc(<?= $attributes[\'height\'] > 0 ? \'300% * \'.$attributes[\'height\'].\'/\'.$attributes[\'width\'] : \'100%\' ?>));
                path { fill:<?= $attributes[\'color\'] ?> }
                .gradient-start { stop-color:<?= $attributes[\'color\'] ?> }
                .gradient-stop { stop-color: #fff0 }
            }
            .color-wrapper { background: <?= $attributes[\'color\'] ?>; }
            img {filter: opacity(<?= 1 - $attributes[\'filter-strength\'] ?>)}
        }
    </style>
    <figure id="<?= $blockId ?>" class="style-element">
        <?= $output ?>
        <figcaption class="for-screenreaders-only"><?= $attributes[\'element\'][\'alt\'] ?></figcaption>
    </figure>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79193,
        'title' => 'Paycom Job Listings',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/paycom',
        'description' => '',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_f5793f4330' => array(
                'type' => 'url',
                'name' => 'paycom-url',
                'default' => 'https://www.paycomonline.net/v4/ats/web.php/jobs?clientkey=F1B2F16F396ACD76E460B6AE64550D8E',
                'label' => 'Paycom Url',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<style>
    #list-jobs {
        list-style: none;
        display: flex;
        flex-basis: 3 auto;
        flex-wrap: wrap;
        flex-direction: row;
        gap: 1rem;
    }
    </style>
    <ul id="list-jobs" class="pods-list">
    </ul>
    <script>
        <?php
        $pageContent = file_get_contents($attributes[\'paycom-url\']);
        $doc = new DOMDocument();
        @$doc->loadHTML($pageContent);
        // $content = $doc->getElementById(\'main-content\');
        $xpath = new DOMXPath($doc);
        $content = $xpath->query(\'//*[@id="main-content"]/script\');
        echo $content->item(0)->textContent;
    ?>
    departmentCodes = {
    	"134": "Development",
    	"135": "Development",
    	"136": "Development",
    	"123": "Research and Evaluation",
    	"134": "Marketing",
    	"115": "Operations",
    	"122": "Policy and Government Relations",
    	"117": "Program Management",
    	"110": "Program Operations" ,
    	"116": "Technology",
    	"114": "Talent Management"
    }
    jobs.forEach(j=>{
            const template = document.createElement(\'template\')
            let flags = [j.location.description]
            if(j.title.toLowerCase().includes(\'site manager\'))
                flags.push(\'Site Management\')
            if(![null,\'\'].includes(j.deptcode))
                flags.push(departmentCodes[j.deptcode])
            template.innerHTML = `
                <li class="pods-list-item" data-filter-on="${flags.join(\' \')}">
                        <h4><a href="https://www.paycomonline.net${j.url}">${j.title}</a></h4>
                        <span>${j.position_description} | ${j.location.description}</span>
                        <p>${j.description}</p>
                </li>
    `
            document.getElementById(\'list-jobs\').appendChild(template.content)
    })
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 79068,
        'title' => 'Automatic Search from URL',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.01 14h-.8l-.27-.27c.98-1.14 1.57-2.61 1.57-4.23 0-3.59-2.91-6.5-6.5-6.5s-6.5 3-6.5 6.5H2l3.84 4 4.16-4H6.51C6.51 7 8.53 5 11.01 5s4.5 2.01 4.5 4.5c0 2.48-2.02 4.5-4.5 4.5-.65 0-1.26-.14-1.82-.38L7.71 15.1c.97.57 2.09.9 3.3.9 1.61 0 3.08-.59 4.22-1.57l.27.27v.79l5.01 4.99L22 19l-4.99-5z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/automatic-search-from-url',
        'description' => 'For automatically searching from the URL, like in the case of a 404 errorWill',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => 'Will be replaced with results on page.',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $search = preg_replace(\'/[^\\w]/\',\' \',$_SERVER[\'REQUEST_URI\'].\' \'.$_SERVER[\'QUERY_STRING\']);
    $args = array(\'s\'=>$search);
    $query = new WP_Query($args);
    if ($query->have_posts() ) {
        $header =<<<EOD
    <h3>Search Results for: $search</h3>
    <ul class="wp-block-latest-posts__list wp-block-latest-posts">
    EOD;
    	_e($header);
            while ( $query->have_posts() ) {
                 $query->the_post();
    ?>
            <li>
        		<div class="wp-block-latest-posts__featured-image alignleft">
        			<a href="https://next.jstart.org/newsflash/jumpstarts-august-newsflash/" aria-label="August Newsflash – Policy Updates and Supporter spotlight">
        				<?php the_post_thumbnail(); ?>
        			</a>
        		</div>
        		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        		<div class="wp-block-latest-posts__post-excerpt">
        			<?php the_excerpt(); ?>
        		</div>
            </li>
    <?php
            }
            _e(\'</ul>\');
    }else{
    ?>
    	<div class="alert alert-info">
    		<p>Sorry, but nothing matched your search criteria.</p>
    	</div>
    <?php } ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78952,
        'title' => 'POD Custom List',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4 4h7V2H4c-1.1 0-2 .9-2 2v7h2V4zm6 9l-4 5h12l-3-4-2.03 2.71L10 13zm7-4.5c0-.83-.67-1.5-1.5-1.5S14 7.67 14 8.5s.67 1.5 1.5 1.5S17 9.33 17 8.5zM20 2h-7v2h7v7h2V4c0-1.1-.9-2-2-2zm0 18h-7v2h7c1.1 0 2-.9 2-2v-7h-2v7zM4 13H2v7c0 1.1.9 2 2 2h7v-2H4v-7z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/pod-single-item-logo',
        'description' => 'Select one item to show its logo and link',
        'category' => 'pods',
        'category_label' => 'pods',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_901ad443c2' => array(
                'type' => 'select_dynamic',
                'name' => 'supporter',
                'default' => '',
                'label' => 'Supporter',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'true',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'post',
                'post_type' => 'supporter',
            ),
            'control_9698484357' => array(
                'type' => 'select_dynamic',
                'name' => 'site',
                'default' => '',
                'label' => 'Site',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'true',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'post',
                'post_type' => 'site',
            ),
            'control_6f8b9b4d1a' => array(
                'type' => 'select_dynamic',
                'name' => 'person',
                'default' => '',
                'label' => 'Person',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'true',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'post',
                'post_type' => 'person',
            ),
            'control_e6e86b4e2d' => array(
                'type' => 'number',
                'name' => 'width',
                'default' => '30',
                'label' => 'Block Width',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '5',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_4229504bef' => array(
                'type' => 'radio',
                'name' => 'width-unit',
                'default' => '%',
                'label' => 'Block Width Unit',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => '%',
                        'value' => '%',
                    ),
                    array(
                        'label' => 'REM',
                        'value' => 'rem',
                    ),
                    array(
                        'label' => 'PX',
                        'value' => 'px',
                    ),
                    array(
                        'label' => 'EM',
                        'value' => 'em',
                    ),
                    array(
                        'label' => 'VW',
                        'value' => 'vw',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_7aa87c4129' => array(
                'type' => 'toggle',
                'name' => 'hide-name',
                'default' => '',
                'label' => 'Hide Name',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_320aff4ca1' => array(
                'type' => 'radio',
                'name' => 'align',
                'default' => 'center',
                'label' => 'Align',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Center',
                        'value' => 'center',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $divId = \'pods-custom-list\'.rand(1000,9999); ?>
    <style>
        #<?= $divId ?> {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: center;
            justify-content: center;
            div {
                text-align: <?= $attributes[\'align\'] || \'center\' ?>;
                width: <?= $attributes[\'width\'] ?><?= $attributes[\'width-unit\']?>;
            }
        }
    </style>
    <div id="<?= $divId ?>">
    <?php
    $ids = array_values(array_diff([ $attributes[\'supporter\'], $attributes[\'site\'], $attributes[\'person\'] ], [null]));
    if(count($ids) == 0) {
        echo "Please select an item";
        return;
    }
    foreach ($ids[0] as $podId) {
        $podType = empty($attributes[\'supporter\']) ? (empty($attributes[\'site\']) ? \'person\' : \'site\') : \'supporter\';
        $item = pods($podType, $podId);
        if(isset($item) && $item->exists()) {
            $link = \'/\'.$podType.\'/\'.$item->display(\'post_name\');
            if($attributes[\'hide-name\'])
                $title = \'\';
            else
                $title = \'<h3>\'.$item->display(\'post_title\').\'</h3>\';
            $image = $item->display(\'logo._img.medium_large\');
            $image = empty($image) ? $item->display(\'picture._img.medium_large\') : $image;
            $width = $attributes[\'width\'].$attributes[\'width-unit\'];
            echo <<<EOD
        <div>
            <a class="pods-single-item" href="$link">
                $image
                $title
            </a>
        </div>
    EOD;
        } else {
            echo "<!-- Item $podId not found -->";
        }
    }
    ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78852,
        'title' => 'Shopify Listing',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/shopify-listing',
        'description' => '',
        'category' => 'text',
        'category_label' => 'text',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_4b6b7c4047' => array(
                'type' => 'radio',
                'name' => 'type',
                'default' => 'collection',
                'label' => 'Type',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Collection',
                        'value' => 'collection',
                    ),
                    array(
                        'label' => 'Product',
                        'value' => 'product',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => 'label',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6b485a415c' => array(
                'type' => 'text',
                'name' => 'collection-name',
                'default' => '',
                'label' => 'Collection Name',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6db9bb484a' => array(
                'type' => 'text',
                'name' => 'product-id',
                'default' => '',
                'label' => 'Product ID',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $useCollection = strtolower($attributes[\'type\']) == \'collection\' && !empty($attributes[\'collection-name\']);
    $useProduct = strtolower($attributes[\'type\']) == \'product\' && !empty($attributes[\'product-id\']);
    if(!($useCollection || $useProduct)) {
        echo \'You must fill in the collection name or product id\';
        return;
    }
    if($useCollection)
        $properties = \'collection="\'.$attributes[\'collection-name\'].\'"\';
    if($useProduct)
        $properties = \'product_id="\'.$attributes[\'product-id\'].\'"\';
    echo do_shortcode("[wps_products $properties]");
    ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78684,
        'title' => 'Document Gallery',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/document-gallery',
        'description' => '',
        'category' => 'text',
        'category_label' => 'text',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_5dda9f4639' => array(
                'type' => 'select_dynamic',
                'name' => 'categories',
                'default' => '',
                'label' => 'Categories',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'true',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'taxonomy',
                'taxonomy_type' => 'category',
            ),
            'control_2ef8cf479e' => array(
                'type' => 'radio',
                'name' => 'display-style',
                'default' => 'List',
                'label' => 'Display Style',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'List',
                        'value' => 'list',
                    ),
                    array(
                        'label' => 'Grid',
                        'value' => 'grid',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_34fb9942a4' => array(
                'type' => 'toggle',
                'name' => 'show-images',
                'default' => '',
                'label' => 'Show Images',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_4f1bce4235' => array(
                'type' => 'toggle',
                'name' => 'show-descriptions',
                'default' => '',
                'label' => 'Show Descriptions',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_51991f4858' => array(
                'type' => 'toggle',
                'name' => 'show-read-more',
                'default' => '',
                'label' => 'Show "Read More"',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_50cadf4242' => array(
                'type' => 'number',
                'name' => 'limit',
                'default' => '20',
                'label' => 'Limit',
                'help' => 'How many documents you would like listed',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<div class="style-<?= strtolower($attributes[\'display-style\']) ?> <?= $attributes[\'show-images\'] ? \'\' : \'hide-images \' ?><?= $attributes[\'show-read-more\'] ? \'\' : \'hide-read-more\' ?>">
    <?php
        $categories = array();
        foreach ($attributes[\'categories\'] as $c) { array_push($categories,get_cat_name($c));
        }
        echo do_shortcode(\'[dg id=-1 limit=\'.$attributes[\'limit\'].\' category="\'.implode(\',\',$categories).\'" \'.($attributes[\'show-descriptions\'] ? \'descriptions=true\' : \'descriptions=false\').\']\');
    ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78330,
        'title' => 'Include Page',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/include-page',
        'description' => '',
        'category' => 'embed',
        'category_label' => 'embed',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_c25b044d0f' => array(
                'type' => 'select_dynamic',
                'name' => 'page-to-include',
                'default' => '',
                'label' => 'Page to Include',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'false',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'page',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '<?php
    if(!empty($attributes[\'page-to-include\'])) {
        echo \'<a href="https://next.jstart.org/wp-admin/post.php?post=\'.$attributes[\'page-to-include\'].\'&action=edit">Go to selected page</a>\';
    } else {
        echo \'Select a page to include\';
    }
    ?>',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    if(!empty($attributes[\'page-to-include\'])) {
        $page = get_post($attributes[\'page-to-include\'], OBJECT, \'display\');
        echo apply_filters(\'the_content\', $page->post_content);
    } else {
        echo \'Select a page to include\';
    }
    ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78227,
        'title' => 'Tab Animator',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M22 5.72l-4.6-3.86-1.29 1.53 4.6 3.86L22 5.72zM7.88 3.39L6.6 1.86 2 5.71l1.29 1.53 4.59-3.85zM12.5 8H11v6l4.75 2.85.75-1.23-4-2.37V8zM12 4c-4.97 0-9 4.03-9 9s4.02 9 9 9c4.97 0 9-4.03 9-9s-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/tab-animator',
        'description' => 'Add this to one tab in a set to animate it',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_b56a8a4241' => array(
                'type' => 'number',
                'name' => 'timing-in-seconds',
                'default' => '5',
                'label' => 'Timing in Seconds',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '30',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $animatorId = \'animator\'.random_int(100,999);
    ?>
    <i id="<?= $animatorId ?>"></i>
    <script>
        setInterval(()=>{
            let nextTab = 0
            document.getElementById(\'<?= $animatorId ?>\')
                .closest(\'.wp-block-themeisle-blocks-tabs\')
                .querySelectorAll(\'.wp-block-themeisle-blocks-tabs__header_item\')
                .forEach((t,i)=>{
                    if(i == 0 && t.getAttribute(\'aria-selected\') == undefined)
                        t.click()
                    if(t.getAttribute(\'aria-selected\') == \'true\')
                        !!t.nextSibling ? nextTab = i+1 : null
                    else if(i == nextTab)
                        setTimeout(()=>t.click(),20)
                })
        }, <?= $attributes[\'timing-in-seconds\'] * 1000 ?>)
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'never',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78160,
        'title' => 'Post List by Category',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/post-list-by-category',
        'description' => '',
        'category' => 'text',
        'category_label' => 'text',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_f48a1944ac' => array(
                'type' => 'text',
                'name' => 'category-slugs',
                'default' => '',
                'label' => 'Category Slugs',
                'help' => 'Enter the category slugs separated by commas',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_ee28bd4cfa' => array(
                'type' => 'select_dynamic',
                'name' => 'category-selections',
                'default' => '',
                'label' => 'Category Selections',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'true',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'taxonomy',
                'taxonomy_type' => 'category',
            ),
            'control_b8cae44c3d' => array(
                'type' => 'select',
                'name' => 'header',
                'default' => '3',
                'label' => 'Header',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'H2',
                        'value' => 'h2',
                    ),
                    array(
                        'label' => 'H3',
                        'value' => 'h3',
                    ),
                    array(
                        'label' => 'H4',
                        'value' => 'h4',
                    ),
                    array(
                        'label' => 'H5',
                        'value' => 'h5',
                    ),
                    array(
                        'label' => 'Plain',
                        'value' => 'div',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_fa7be5482e' => array(
                'type' => 'number',
                'name' => 'preview-length',
                'default' => '100',
                'label' => 'Preview Length',
                'help' => 'The number of characters to preview from if there is no excerpt',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '',
                'max' => '100',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_8f790c41bd' => array(
                'type' => 'toggle',
                'name' => 'use-featured-image',
                'default' => '',
                'label' => 'Use Featured Image',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_218af04ffe' => array(
                'type' => 'toggle',
                'name' => 'show-publication-date',
                'default' => '',
                'label' => 'Show Publication Date',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $categoryIDs = array();
    if(isset($attributes[\'category-slugs\'])) {
        global $wpdb;
        $joinedSlugs = str_replace(\' \',\'-\',strtolower("\'".implode("\',\'", array_map(\'trim\', explode(",", $attributes[\'category-slugs\'])))."\'"));
        $ids =    $wpdb->get_results("SELECT term_taxonomy_id as id FROM ybb_term_taxonomy WHERE term_id in (SELECT term_id FROM ybb_terms where slug IN ($joinedSlugs)) and taxonomy = \'category\'");
        foreach($ids as $i) {
            $categoryIDs[] = $i->id;
        }
    }
    $categoryIDs = [...$categoryIDs, ...$attributes[\'category-selections\']];
    if(empty($categoryIDs))
        echo \'<h3>No news on that yet!</h3>\';
    else {
        $posts = get_posts(array(\'category\' => implode(\',\', $categoryIDs), \'post_type\' => \'post\', \'post_status\' => \'publish\'));
        echo \'<ul class="post-category-list">\';
        foreach($posts as $post) {
            $featuredImage = \'\';
            if($attributes[\'use-featured-image\'] && has_post_thumbnail($post->ID))
                $featuredImage = \'<div class="posts-by-category-featured-image"><a href="/news/\'.$post->post_name.\'" style="background-image:url(\'.get_the_post_thumbnail_url($post->ID, \'medium_large\').\')"><span class="for-screenreaders-only">\'.get_post_meta( get_post_thumbnail_id(), \'_wp_attachment_image_alt\', true ).\'</span></a></div>\';
            $output = <<<POST
            <li class="post-category-item">
                $featuredImage
                <div>
                    <${attributes[\'header\']}>
                        <a href="/news/{$post->post_name}">{$post->post_title}</a>
    POST;
                if($attributes[\'show-publication-date\'])
                    $output .= "<span>{$post->post_date}</span>";
                $output .=    "     </${attributes[\'header\']}>";
                $content = wp_strip_all_tags(empty($post->post_excerpt) ? $post->post_content : $post->post_excerpt);
                $output .= $attributes[\'preview-length\'] ? trim(strlen($content) > $attributes[\'preview-length\'] ? substr($content, 0, $attributes[\'preview-length\']) : $content).\'...\' : \'\';
            $output .= \'<a href="/news/\'.$post->post_name.\'" class="read-more">Read more</a></div></li>\';
            echo $output;
        }
        echo \'</ul>\';
    }
    ?>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78142,
        'title' => 'Program Statistics from Salesforce',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/program-statistics',
        'description' => '',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_9b2be94dbe' => array(
                'type' => 'text',
                'name' => 'state',
                'default' => '',
                'label' => 'State (two letter abbreviation)',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '2',
            ),
            'control_040ac54eff' => array(
                'type' => 'text',
                'name' => 'site-myjstart-id',
                'default' => '',
                'label' => 'Site MyJStart ID',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_d658b346b9' => array(
                'type' => 'toggle',
                'name' => 'all-time',
                'default' => '',
                'label' => 'All Time',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_7d7a4544bb' => array(
                'type' => 'select',
                'name' => 'single-statistic',
                'default' => '',
                'label' => 'Single Statistic',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'All',
                        'value' => '',
                    ),
                    array(
                        'label' => 'Children',
                        'value' => 'Children',
                    ),
                    array(
                        'label' => 'Classrooms',
                        'value' => 'Classrooms',
                    ),
                    array(
                        'label' => 'Corps Members',
                        'value' => 'Corps Members',
                    ),
                    array(
                        'label' => 'Sites',
                        'value' => 'Sites',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_377bc2427f' => array(
                'type' => 'toggle',
                'name' => 'number-only',
                'default' => '',
                'label' => 'Number Only',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'false',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $statBlockID = \'programStatistics\'.rand(1001,9999);
    global $wpdb;
    $iconData =    $wpdb->get_results("SELECT distinct post_title, guid FROM ybb_posts where post_type=\'attachment\' AND post_title like \'icon-%\' ORDER BY post_date desc");
    $icons = array();
    foreach($iconData as $i) {
        $icons[$i->post_title] = $i->guid;
    }
    ?>
    <div class="program-statistics" id="<?= $statBlockID ?>">
        <div style="width:100%;margin:0 auto;text-align:center">
            <progress-spinner color="var(--ast-global-color-0)" size="4rem"></progress-spinner>
        </div>
    </div>
    <script>
        fetch("https://windmill.jstart.org/api/w/opstech/jobs/run_wait_result/f/f/website/get_program_statistics", {
            method: \'POST\',
            mode: \'cors\',
            headers: {
    		    "Content-Type": "application/json",
    		    "Authorization": "Bearer TOKEN"
    		},
    		body: JSON.stringify({
    	        "Site MyJStart ID": "<?= $attributes[\'site-myjstart-id\'] ?>",
    	        "State": "<?= $attributes[\'state\'] ?>",
    	        "All Time": <?= $attributes[\'all-time\'] ? \'true\' : \'false\' ?>
        	})
        }).then(r=>r.json()).then(r=>{
            const icons = <?= stripcslashes(json_encode($icons)); ?>;
            document.getElementById("<?= $statBlockID ?>").innerHTML = Object.keys(r).sort().filter(i=>(r[i] > 1 && i.includes(\'<?= $attributes[\'single-statistic\'] ?>\'))).map(i=>{
                const icon = icons["icon-" + i.toLowerCase().replaceAll(\' \',\'\')]
                let output
                const number = (new Intl.NumberFormat(\'en-US\')).format(r[i])
                const numberOnly = <?= $attributes[\'number-only\'] ? "true\\n" : "false\\n"; ?>
                if(!numberOnly)
                    output = `
        <img alt="icon of ${i}" src="${icon}"/>
        <h3>${number}</h3>
        <p>${i}</p>
    `
                else
                    output = `<h3>${number}</h3>`
                return `
    <div class="stat-block">
        ${output}
    </div>
    `
        }).join(\'\')
    })
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78064,
        'title' => 'Anchor',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/anchor',
        'description' => '',
        'category' => 'design',
        'category_label' => 'design',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_dfbba14c5e' => array(
                'type' => 'text',
                'name' => 'id',
                'default' => '',
                'label' => 'ID',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'html',
            'editor_html' => '<a href="#" id="{{id}}">Anchor ID: {{id}}</a>',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<a href="#" id="{{id}}"></a>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 78012,
        'title' => 'Content Block',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/content-block',
        'description' => '',
        'category' => 'pods',
        'category_label' => 'pods',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_f76a1b481e' => array(
                'type' => 'select_dynamic',
                'name' => 'content-item',
                'default' => '',
                'label' => 'Content Item',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'multiple' => 'false',
                'conditional' => '',
                'placeholder' => '',
                'characters_limit' => '',
                'entity_type' => 'post',
                'post_type' => 'content',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $c = pods( \'content\', $attributes[\'content-item\']);
    ?>
    <div class="content-block">
        <?= $c->exists() ? $c->display(\'content_body\') : "No content found" ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 77946,
        'title' => 'Program Partner List',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.5 13c-1.2 0-3.07.34-4.5 1-1.43-.67-3.3-1-4.5-1C5.33 13 1 14.08 1 16.25V19h22v-2.75c0-2.17-4.33-3.25-6.5-3.25zm-4 4.5h-10v-1.25c0-.54 2.56-1.75 5-1.75s5 1.21 5 1.75v1.25zm9 0H14v-1.25c0-.46-.2-.86-.52-1.22.88-.3 1.96-.53 3.02-.53 2.44 0 5 1.21 5 1.75v1.25zM7.5 12c1.93 0 3.5-1.57 3.5-3.5S9.43 5 7.5 5 4 6.57 4 8.5 5.57 12 7.5 12zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 5.5c1.93 0 3.5-1.57 3.5-3.5S18.43 5 16.5 5 13 6.57 13 8.5s1.57 3.5 3.5 3.5zm0-5.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/program-partner-list',
        'description' => '',
        'category' => 'lazyblocks',
        'category_label' => 'lazyblocks',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_9b2be94dbe' => array(
                'type' => 'text',
                'name' => 'state',
                'default' => '',
                'label' => 'State (two letter abbreviation)',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '2',
            ),
            'control_040ac54eff' => array(
                'type' => 'text',
                'name' => 'site-myjstart-id',
                'default' => '',
                'label' => 'Site MyJStart ID',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $listID = \'programPartnerList\'.rand(1001,9999);
    ?>
    <div id="<?= $listID ?>">
        <div style="width:100%;margin:0 auto">
            <progress-spinner color="var(--ast-global-color-0)" size="4rem"></progress-spinner>
        </div>
    </div>
    <script>
        fetch("https://windmill.jstart.org/api/w/opstech/jobs/run_wait_result/f/f/website/get_program_partners", {
            method: \'POST\',
            mode: \'cors\',
            headers: {
    		    "Content-Type": "application/json",
    		    "Authorization": "Bearer TOKEN"
    		},
    		body: JSON.stringify({
    	        "Site MyJStart ID": "<?= $attributes[\'site-myjstart-id\'] ?>",
    	        "State": "<?= $attributes[\'state\'] ?>"
        	})
        }).then(r=>r.text()).then(r=>document.getElementById("<?= $listID ?>").innerHTML = JSON.parse(r))
    </script>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 77775,
        'title' => 'Power BI Embed',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/power-bi-embed',
        'description' => '',
        'category' => 'embed',
        'category_label' => 'embed',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_f02bf249f0' => array(
                'type' => 'url',
                'name' => 'power-bi-public-embed-url',
                'default' => '',
                'label' => 'Power BI Public Embed URL',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_ff9b364351' => array(
                'type' => 'text',
                'name' => 'title',
                'default' => '',
                'label' => 'Title',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_334a65413d' => array(
                'type' => 'number',
                'name' => 'width',
                'default' => '1200',
                'label' => 'Width (before cropping)',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '10',
                'max' => '1600',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_b5983147b7' => array(
                'type' => 'number',
                'name' => 'height',
                'default' => '600',
                'label' => 'Height (before cropping)',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '10',
                'max' => '1600',
                'step' => '1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_286a024dd2' => array(
                'type' => 'toggle',
                'name' => 'hide-tabs',
                'default' => '',
                'label' => 'Hide Tabs?',
                'help' => 'Show or hide navigation tabs',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'checked' => 'true',
                'alongside_text' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    $adjustedHeight = $attributes[\'hide-tabs\'] ? $attributes[\'height\'] - 60 : $attributes[\'height\'];
    $adjustedWidth = $attributes[\'hide-tabs\'] ? $attributes[\'width\'] - 15 : $attributes[\'width\'];
    ?>
    <div class="powerbi-wrapper" style="overflow:clip;width:<?= $adjustedWidth ?>px;height:<?= $adjustedHeight ?>px;">
        <iframe title="<?= $attributes[\'title\'] ?>" width="<?= $attributes[\'width\'] ?>" height="<?= $attributes[\'height\'] ?>" src="<?= $attributes[\'power-bi-public-embed-url\'] ?>" frameborder="0" allowFullScreen="true"></iframe>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 77707,
        'title' => 'Video Hero Block',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 3H3c-1.11 0-2 .89-2 2v12c0 1.1.89 2 2 2h5v2h8v-2h5c1.1 0 1.99-.9 1.99-2L23 5c0-1.11-.9-2-2-2zm0 14H3V5h18v12zm-5-6l-7 4V7z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/video-hero-block',
        'description' => '',
        'category' => 'media',
        'category_label' => 'media',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_208b304d60' => array(
                'type' => 'image',
                'name' => 'image',
                'default' => '',
                'label' => 'Poster Image',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'insert_from_url' => 'true',
                'preview_size' => 'full',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_bbf8a0451b' => array(
                'type' => 'url',
                'name' => 'videoUrl',
                'default' => '',
                'label' => 'Video URL',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $blockId = \'video-hero\'.rand(1001,9999); ?>
    <style type="text/css" media="all">
        .video-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .video-wrapper video {
            object-fit: cover;
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        .video-wrapper .video-content { position:relative; }
    </style>
    <div id="<?= $blockId ?>" class="video-wrapper">
        <video playsinline preload="auto" autoplay muted loop poster="<?= $attributes[\'image\'][\'url\'] ?>">
            <source src="<?= $attributes[\'videoUrl\'] ?>">
            Your browser does not support the video tag.
        </video>
        <div class="video-content">
            <InnerBlocks />
        </div>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 77648,
        'title' => 'PODs List View Filter',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>',
        'keywords' => array(
            0 => 'filter',
            1 => 'search',
        ),
        'slug' => 'lazyblock/pods-list-view-filter',
        'description' => 'Adds a drop-down or group of buttons to a page that will sort a PODs list view dynamically. Multiple filter controls can be used for one list view, BUT only have one list view on a page',
        'category' => 'pods',
        'category_label' => 'pods',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_7cd9704ff3' => array(
                'type' => 'radio',
                'name' => 'filterType',
                'default' => '',
                'label' => 'Filter Type',
                'help' => 'Use drop-downs for long lists and buttons for short',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Drop-down',
                        'value' => 'select',
                    ),
                    array(
                        'label' => 'Buttons',
                        'value' => 'buttons',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_66ca0b456f' => array(
                'type' => 'repeater',
                'name' => 'filterOptions',
                'default' => '',
                'label' => 'Filter Options',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'rows_min' => '1',
                'rows_max' => '',
                'rows_label' => 'Option {{#}}: {{label}}',
                'rows_add_button_label' => '+ Add Option',
                'rows_collapsible' => 'true',
                'rows_collapsed' => 'true',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_899aed4b65' => array(
                'type' => 'text',
                'name' => 'label',
                'default' => '',
                'label' => 'Label',
                'help' => '',
                'child_of' => 'control_66ca0b456f',
                'placement' => 'content',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => 'The text that visitors will see',
                'characters_limit' => '',
            ),
            'control_0bea254a04' => array(
                'type' => 'text',
                'name' => 'matchingText',
                'default' => '',
                'label' => 'Matching Text',
                'help' => 'Leave this blank to clear filters',
                'child_of' => 'control_66ca0b456f',
                'placement' => 'content',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => 'Text for partial match on PODs list item metadata',
                'characters_limit' => '',
            ),
            'control_7c19aa4fd8' => array(
                'type' => 'number',
                'name' => 'fontSize',
                'default' => '1',
                'label' => 'Font Size',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '0',
                'max' => '',
                'step' => '0.1',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_fcba914d51' => array(
                'type' => 'select',
                'name' => 'fontSizeUnit',
                'default' => 'rem',
                'label' => 'Font Size Unit',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'PX',
                        'value' => 'px',
                    ),
                    array(
                        'label' => '%',
                        'value' => '%',
                    ),
                    array(
                        'label' => 'View Width',
                        'value' => 'vw',
                    ),
                    array(
                        'label' => 'Em',
                        'value' => 'em',
                    ),
                    array(
                        'label' => 'Rem',
                        'value' => 'rem',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_292bdd4d51' => array(
                'type' => 'text',
                'name' => 'width',
                'default' => '',
                'label' => 'Width',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => 'enter a valid CSS value for width',
                'characters_limit' => '',
            ),
            'control_5a3b7e41b9' => array(
                'type' => 'select',
                'name' => 'alignment',
                'default' => 'center',
                'label' => 'Alignment',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Left',
                        'value' => 'left',
                    ),
                    array(
                        'label' => 'Center',
                        'value' => 'center',
                    ),
                    array(
                        'label' => 'Right',
                        'value' => 'right',
                    ),
                ),
                'allow_null' => 'false',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_375ab84c1a' => array(
                'type' => 'color',
                'name' => 'button-color',
                'default' => '',
                'label' => 'Button Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_febb4d4fbd' => array(
                'type' => 'color',
                'name' => 'text-color',
                'default' => '',
                'label' => 'Text Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_6dbb434207' => array(
                'type' => 'color',
                'name' => 'hover-color',
                'default' => '',
                'label' => 'Hover Color',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'alpha' => 'false',
                'palette' => 'true',
                'alongside_text' => '',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_a7b9694b74' => array(
                'type' => 'text',
                'name' => 'defaultFilter',
                'default' => '',
                'label' => 'Default Filter',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => '',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php $controlSelector = \'podsListFilter\'.rand(1001,9999); ?>
    <style>
    /* might move this somewhere else, not bad to have it repeat just not necessary */
    @keyframes slideaway {
        from { display: flex; }
        to { transform: scale(0.25); opacity: 0; max-width:25%}
    }
    .pods-hide { animation: slideaway 200ms; }
    .pods-hidden { display:none }
    
    .<?= $controlSelector ?> {
     display: flex;
     text-align: <?= $attributes[\'alignment\'] ?>;
     width: <?= $attributes[\'width\'] ?>;
         .wp-block-button    {
            line-height: 3rem;
            .wp-block-button__link {
                background: <?= $attributes[\'button-color\'] ? $attributes[\'button-color\'] : \'var(--ast-global-color-0)\' ?>;
                color: <?= $attributes[\'text-color\'] ? $attributes[\'text-color\'] : \'var(--ast-global-color-5)\' ?>;
            }
    
            .wp-block-button__link:hover {
                background: <?= $attributes[\'hover-color\'] ? $attributes[\'hover-color\'] : \'var(--ast-global-color-0)\' ?>;
            }
        }
        select, .wp-block-button a {
            font-size: <?= $attributes[\'fontSize\']; ?><?= $attributes[\'fontSizeUnit\']; ?>;
        }
    }
    </style>
    <script>
    // making sure we don\'t try to double create function
    if(typeof window.filterListItems === "undefined") {
    	window.filterListItems = (filterOn)=>{
    		const filter = [null,"",undefined].includes(filterOn) ? "*" : `[data-filter-on*=\'${filterOn}\']`
    		Array.from(document.querySelectorAll(`.pods-list-item:where(${filter})`)).forEach(i=>{
    			i.classList.remove("pods-hidden")
    			i.classList.remove("pods-hide")
    		})
    		Array.from(document.querySelectorAll(`.pods-list-item:not(${filter})`)).forEach(i=>{
    			i.classList.add("pods-hide")
    			setTimeout(()=>i.classList.add("pods-hidden"), 150)
    		})
    	}
    }
    </script>
    <div class="pods-list-view-filter <?= $controlSelector ?>">
    <?php if($attributes[\'filterType\'] == \'select\') { ?>
        <select onchange="filterListItems(this.value)">
            <?php foreach($attributes[\'filterOptions\'] as $o) { ?>
                <option value="<?= $o[\'matchingText\'] ?>"><?= $o[\'label\'] ?></option>
            <?php } ?>
        </select>
    <?php } else if($attributes[\'filterType\'] == \'buttons\') { ?>
        <div class="wp-block-buttons">
            <?php foreach ($attributes[\'filterOptions\'] as $o) { ?>
                <div class="wp-block-button">
                    <a class="wp-block-button__link wp-element-button" href="javascript:filterListItems(\'<?= $o[\'matchingText\'] ?>\')">
                        <?= $o[\'label\'] ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <script>
        <?php if($attributes[\'defaultFilter\']) { ?>
        document.addEventListener("DOMContentLoaded", (event) => {
            filterListItems(\'<?= $attributes[\'defaultFilter\'] ?>\')
        })
        <?php    } ?>
    </script>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => true,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
    lazyblocks()->add_block( array(
        'id' => 1208,
        'title' => 'Cognito Form',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 3H4.99c-1.11 0-1.98.9-1.98 2L3 19c0 1.1.88 2 1.99 2H19c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 12h-4c0 1.66-1.35 3-3 3s-3-1.34-3-3H4.99V5H19v10zm-3-5h-2V7h-4v3H8l4 4 4-4z" /></svg>',
        'keywords' => array(
        ),
        'slug' => 'lazyblock/cognito-form',
        'description' => 'Loads a Cognito form by ID and optionally parses parameters to prefill the form:
    i to use the Short ID for a contact
    f to use the Salesforce ID of any record
    e to use the Salesforce ID of an external partner',
        'category' => 'embed',
        'category_label' => 'embed',
        'supports' => array(
            'customClassName' => true,
            'anchor' => false,
            'html' => false,
            'multiple' => true,
            'inserter' => true,
            'reusable' => true,
            'lock' => true,
            'align' => array(
                0 => 'wide',
                1 => 'full',
            ),
            'ghostkit' => array(
                'effects' => false,
                'position' => false,
                'spacings' => false,
                'frame' => false,
                'transform' => false,
                'customCSS' => false,
                'display' => false,
                'attributes' => false,
            ),
        ),
        'controls' => array(
            'control_f509d84191' => array(
                'type' => 'number',
                'name' => 'CognitoFormID',
                'default' => '',
                'label' => 'Cognito Form ID',
                'help' => '',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'true',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'min' => '1',
                'max' => '',
                'step' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
            'control_cc280941bd' => array(
                'type' => 'radio',
                'name' => 'PrefillRecordType',
                'default' => '',
                'label' => 'Process Prefill for Record Type',
                'help' => 'Leave blank to skip prefilling',
                'child_of' => '',
                'placement' => 'inspector',
                'width' => '100',
                'hide_if_not_selected' => 'false',
                'required' => 'false',
                'translate' => 'false',
                'save_in_meta' => 'false',
                'save_in_meta_name' => '',
                'choices' => array(
                    array(
                        'label' => 'Contact',
                        'value' => 'contact',
                    ),
                    array(
                        'label' => 'Lead',
                        'value' => 'lead',
                    ),
                    array(
                        'label' => 'Volunteer Registration',
                        'value' => 'volunteer_registration__c',
                    ),
                ),
                'allow_null' => 'true',
                'multiple' => 'false',
                'output_format' => '',
                'placeholder' => '',
                'characters_limit' => '',
            ),
        ),
        'code' => array(
            'output_method' => 'php',
            'editor_html' => 'Form will be included on page',
            'editor_callback' => '',
            'editor_css' => '',
            'frontend_html' => '<?php
    	$htmlFormId = \'cognitoForm\'.rand(1000,9999);
    	$CognitoFormID = $attributes[\'CognitoFormID\'];
    	$ProcessPrefillForRecordType = $attributes[\'PrefillRecordType\'];
    ?>
    <style>
    	.lds-ring {
    		display: inline-block;
    		position: relative;
    		width: 80px;
    		height: 80px;
    	}
    	.lds-ring div {
    		box-sizing: border-box;
    		display: block;
    		position: absolute;
    		width: 64px;
    		height: 64px;
    		margin: 8px;
    		border: 8px solid #fff;
    		border-radius: 50%;
    		animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    		border-color: #fff transparent transparent transparent;
    	}
    	.lds-ring div:nth-child(1) { animation-delay: -0.45s; }
    	.lds-ring div:nth-child(2) { animation-delay: -0.3s; }
    	.lds-ring div:nth-child(3) { animation-delay: -0.15s; }
    	@keyframes lds-ring {
    		0% { transform: rotate(0deg); }
    		100% { transform: rotate(360deg); }
    	}
    	#<?= $htmlFormId ?> .hide{display:none !important}
    </style>
    <div id="<?= $htmlFormId ?>">
    	<script src="https://www.cognitoforms.com/f/seamless.js" data-key="e-BI1XP-WEOsUlHyCaRuhQ"></script>
    	<div id="<?= $htmlFormId ?>Form" class="cognito"></div>
    <?php if($ProcessPrefillForRecordType) {
    	$user = wp_get_current_user();
    ?>
    	<div id="spinner" class="lds-ring"><div></div><div></div><div></div><div></div></div>
    	<select class="hide">
    		<option>Select a service record</option>
    	</select>
    	<script>
    		const <?= $htmlFormId ?>Data = {}
    		document.querySelector(\'#<?= $htmlFormId ?> select\').onchange = (e)=>{
    			let o = e.target.selectedOptions[0]
    			Cognito.mount(o.dataset.formId, "#<?= $htmlFormId ?>Form").prefill(<?= $htmlFormId ?>Data[o.dataset.id], { success: function() {
    				document.querySelector(\'#<?= $htmlFormId ?> .spinner\').classList.add(\'hide\')
    			}})
    			document.querySelector(\'#<?= $htmlFormId ?> select\').classList.add(\'hide\')
    		}
    		const init = ()=>{
    			const username = \'<?= $user->user_login; ?>\';
    			const formId = <?= $CognitoFormID ?>;
    			const prefillRecordType = \'<?= $ProcessPrefillForRecordType ?>\'.toLowerCase();
    			const params = new URLSearchParams(window.location.search)
    			const getByShortId = params.get("i")
    			const prefillSalesforceId = params.get("f")
    			const prefillProgramPartnerId = params.get("e")
    			if(!prefillRecordType && !getByShortId)
    				return Cognito.load("forms", { "id": formId })
    			else if(prefillRecordType || getByShortId ) {
    				try {
    					fetch(`https://windmill.jstart.org/api/w/opstech/jobs/run_wait_result/f/f/utilities/get_by_shortid`, {
    						"method": "POST",
    						"headers": {
    							"Content-Type": "application/json",
    							"Authorization": "Bearer TOKEN"
    						},
    						"body": JSON.stringify({
    							"i": params.get("i") || username,
    							"t": params.get("t") || prefillRecordType
    						})
    					}).then(r=>r.text()).then(r=>{
    						try {
    							const loadedData = JSON.parse(atob(JSON.parse(r))) //have to parse the output from Windmill so the btoa can read it right
    							let processedData = {}
    							if(typeof loadedData === \'object\' && !Array.isArray(loadedData)) {
    								Object.keys(loadedData).forEach(k=>{ processedData[k.replace("_","")] = loadedData[k] }) // remove underscores from field names
    								Cognito.mount(formId,"#<?= $htmlFormId ?>Form").prefill(processedData)
    								document.querySelector(\'#<?= $htmlFormId ?> .spinner\').classList.add(\'hide\')
    							} else {
    								const selector = document.querySelector(\'#<?= $htmlFormId ?> select\')
    								for(let i = loadedData.length - 1; i >= 0; i--) {
    									const option = document.createElement(\'option\')
    									option.dataset.id = loadedData[i].Id
    									loadedData[i][\'SalesforceId\'] = loadedData[i].Id
    									<?= $htmlFormId ?>Data[loadedData[i].Id] = loadedData[i]
    									option.dataset.formId = formId
    									switch(prefillRecordType) {
    										case \'volunteer_registration__c\':
    											option.label = loadedData[i].Site_and_Program_Year__c
    											option.value = loadedData[i].Id
    											break
    									}
    									selector.appendChild(option)
    								}
    								selector.classList.remove(\'hide\')
    							}
    						} catch (e) { Cognito.mount(formId, "#<?= $htmlFormId ?>Form"); console.log(e, r) }
    					})
    				} catch (e) {
    					console.log(e)
    				}
    			}
    			else if(prefillSalesforceId) {
    				Cognito.prefill({ "SalesforceId": prefillSalesforceId })
    			}
    			else if(prefillProgramPartnerId) {
    				Cognito.prefill({ "ProgramPartnerId": prefillProgramPartnerId })
    			}
    		}
    		init()
    	</script>
    <?php } else { ?>
    	<script>
    	const initForm<?= $CognitoFormID ?> = ()=>{
        	if(Cognito) {
    	    	Cognito.mount(<?= $CognitoFormID ?>,"#<?= $htmlFormId ?>Form")
        	} else {
        	    setTimeout(initForm<?= $CognitoFormID ?>, 100)
        	}
    	}
    	initForm<?= $CognitoFormID ?>()
    	</script>
    <?php
    } ?>
    </div>',
            'frontend_callback' => '',
            'frontend_css' => '',
            'show_preview' => 'always',
            'single_output' => false,
        ),
        'styles' => array(
        ),
        'condition' => array(
        ),
    ) );
    
} );
