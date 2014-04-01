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
 */

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'PageDisqus',
	'descriptionmsg' => 'pagedisqus-desc',
	'version'        => '0.3.0',
	'author'         => array( 'Michael Platzer', 'Luis Felipe Schenone' ),
	'url'            => 'http://www.mediawiki.org/wiki/Extension:PageDisqus'
);

$wgMessagesDirs['PageDisqus'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['PageDisqus'] = __DIR__ . '/PageDisqus.i18n.php';
$wgAutoloadClasses['PageDisqus'] = __DIR__ . '/PageDisqus.body.php';

$wgHooks['SkinAfterContent'][] = 'PageDisqus::onSkinAfterContent';
$wgHooks['SkinAfterBottomScripts'][] = 'PageDisqus::onSkinAfterBottomScripts';
