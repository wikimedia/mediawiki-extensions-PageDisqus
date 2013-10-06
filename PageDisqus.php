<?php
/**
 * Disqus integration extension
 *
 * @file
 * @ingroup Extensions
 *
 * @author Michael Platzer <michael.platzer@gmail.com>
 * Revised by Angel Guzmán Maeso <shakaran at gmail dot com>
 * Updated by Luis Felipe Schenone <schenonef@gmail.com>
 * @license GPL
 * @version 0.2
 */

$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'PageDisqus',
	'description'    => 'Integrates Disqus commenting service',
	'descriptionmsg' => 'pagedisqus-desc',
	'version'        => 0.2,
	'author'         => array( 'Michael Platzer', 'Luis Felipe Schenone' ),
	'url'            => 'http://www.mediawiki.org/wiki/Extension:PageDisqus'
);

$wgExtensionMessagesFiles['PageDisqus'] = __DIR__ . '/PageDisqus.i18n.php';
$wgAutoloadClasses['PageDisqus'] = __DIR__ . '/PageDisqus.body.php';

$wgHooks['SkinAfterContent'][] = 'PageDisqus::onSkinAfterContent';
$wgHooks['SkinAfterBottomScripts'][] = 'PageDisqus::onSkinAfterBottomScripts';
