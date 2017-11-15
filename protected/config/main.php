<?php
// This is the main Web application configuration. Any writable
// application properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Classified Ads: Free Classified Ads Online',
    
    // autoloading model and component classes
    'import' => array(
		'application.modules.yiiseo.models.*',
        'application.models.*',
        'application.components.*',
		'application.extensions.hoauth.components.*'
    ),
    
    'modules' => array(
        
		'yiiseo'=>array(
        	'class'=>'application.modules.yiiseo.YiiseoModule',
        	'password'=>'111', // your default password is 111
    	),				
        
    ),
	
    'params' => array(
        'listPerPage' => 10
    ),
    
    // application components
    'components' => array(
		'seo'=>array(
				'class' => 'application.modules.yiiseo.components.SeoExt',
				),
		'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true
        ),	
        'uri' => array(
            'class' => 'application.components.URI'
        ),
        'commonFnc' => array(
            'class' => 'application.components.CommonFnc'
        ),
         'sitemap'=>array(
							'class'=>'application.components.SitemapComponent',
							'protocolPattern'=>'https',
							'sitemapPath'=>'/var/www/html/ads'
						),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive'=>false,        
            //'urlSuffix'=>'.html',
            'rules' => array(
                'Signup.html' => 'Site/signup',
                'Signin.html' => 'Site/signin',
                'Forgot.html' => 'Site/Forgot',
                'about-us.html' => 'Site/pages',
                'thank_you.html' => 'Site/Signupthankyou',
                'privacy-policy.html' => 'Site/pages',
                'feedback.html' => 'Page/feedback',
                'contact-us.html' => 'Page/feedback',
                'blog.html' => 'Site/pages',
                'owner.html' => 'Site/pages',
                'advertisewith-us.html' => 'Site/pages',
                'businesslist.html' => 'Site/pages',
                'sitemap.html' => 'Site/pages',
                'terms-conditions.html' => 'Site/pages',
                'help.html' => 'Site/faq',
                'avoid-scams.html' => 'Site/pages',
                'careers.html' => 'Site/pages',
                '<category_type:\w+>/<searchcat>.html' => 'Site/Search',
				'Search-<cat_id:\d+>-<searchcat:\w+>-<category_type:\w+>.html' => 'Site/Search',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            )
        ),
        
        
        
        'db' => require(dirname(__FILE__) . '/database.php'),
        
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error'
        ),
        
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning'
                )
                // uncomment the following to show log messages on web pages
                
            )
        )
        
    )
); 
