<?php
// Copy to config/snap/forms.php
return [
    // Determines whether a Class file needs to be explicitly set to @autodoc comment to be viewed.
    'restrict_auto_docs' => true,
    'toc' => 'auto', /*[
        'Project' => [
            'app' => 'My Docs',
        ],
        'SNAP' => [
            'admin' => 'Admin',
            'ui' => 'UI',
            'form' => 'Forms',
            'database' => 'Database',
        ]
    ],*/
    'allowed_funcs' => [
        //'docs_url',
        //'docs_auto_link',
        //'form_element',
    ]
];