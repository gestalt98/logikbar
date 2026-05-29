<?php
add_action('tgmpa_register', 'nictitate_lite_register_required_plugins');

function nictitate_lite_register_required_plugins() {
    $plugins = array(            
        array(
            'name'               => 'Kopa Framework',
            'slug'               => 'kopatheme',
            'required'           => false,
            'version'            => '1.0.10',
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => ''
        ),
        array(
            'name' => 'Nictitate Toolkit',
            'slug' => 'nictitate-toolkit',
            'required' => false,
            'version' => '1.0.3',
            'force_activation' => false,
            'force_deactivation' => false,
        )
    );

    
    $config = array(        
        'has_notices'  => true,
        'is_automatic' => false
    );
    
    tgmpa($plugins, $config);    
}
