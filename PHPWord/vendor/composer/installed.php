<?php return array(
    'root' => array(
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => NULL,
        'name' => '__root__',
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => NULL,
            'dev_requirement' => false,
        ),
        'laminas/laminas-escaper' => array(
            'pretty_version' => '2.12.0',
            'version' => '2.12.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../laminas/laminas-escaper',
            'aliases' => array(),
            'reference' => 'ee7a4c37bf3d0e8c03635d5bddb5bb3184ead490',
            'dev_requirement' => false,
        ),
        'phpoffice/phpword' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'library',
            'install_path' => __DIR__ . '/../phpoffice/phpword',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'reference' => 'f195d282d03ed7e31384560d4b577969368e2682',
            'dev_requirement' => false,
        ),
    ),
);
