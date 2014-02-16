<?php

	define('SMARTY_DIR', $_SERVER['DOCUMENT_ROOT'].'/extlibs/smarty/');
	define('TEMPLATES_DIR', $_SERVER['DOCUMENT_ROOT'].'/templates');

	require_once(SMARTY_DIR . 'SmartyBC.class.php');

	$smarty = new SmartyBC();

	$smarty->setTemplateDir(array(
    TEMPLATES_DIR,
    TEMPLATES_DIR . '/partials',
    TEMPLATES_DIR . '/layouts',
	));

	$smarty->setCompileDir(SMARTY_DIR . 'templates_c/')
       	 ->setConfigDir(SMARTY_DIR . 'configs/')
         ->setCacheDir(SMARTY_DIR . 'cache/');

	$smarty->caching = true;
	$smarty->cache_lifetime = 120;
	//$smarty->force_compile = true;
	//$smarty->debugging = true;

	$smarty->assign('site',array(
		'author' => 'Julia Kurnia',
		'url'=>'http://zidisha.org', 
		'email_contact' => 'julia@zidisha.org'
	));

	$smarty->assign('twitter',array(
		'screenname' => 'bestpsdfreebies',
		'key'=>'BOmazbuKUiXqpvcdBtuXbw', 
		'secret'=>'mCt9uC3hi8W7QhzNHRBisg6cEqLKa5bKtmSzQ3Jwc', 
		'token'=>'478633957-N08fggOglJNe5GUMTYeng4xcpc1gvLOe4U4W0u1g',
		'token_secret'=>'eb7vaneoNRizPwIDOQktv8wgmNeSKBMdxUXzmHuRQ',
		'cache_expire'=>3600,
		'tweets'=>3,
	));

	// Need to make template_c and cache folders writeable. 
	// Navigate to libs folder and run these commands in terminal:

	// sudo chown nobody:nobody templates_c
	// sudo chmod 775 templates_c

	// sudo chown nobody:nobody cache
	// sudo chmod 775 cache

?>