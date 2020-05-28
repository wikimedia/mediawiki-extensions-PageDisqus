<?php

class PageDisqus {

	static function onSkinAfterContent( &$data, $skin )  {
		global $mediaWiki,
			$wgPageDisqusShortname,
			$wgPageDisqusPageBlacklist,
			$wgPageDisqusCategoryBlacklist,
			$wgPageDisqusNamespaceWhitelist;

		if ( empty( $wgPageDisqusShortname ) ) {
			exit( wfMessage( 'pagedisqus-shortname' ) );
		}

		if ( $mediaWiki->getAction() !== 'view' ) {
			return true;
		}

		if ( !in_array( $skin->getTitle()->getNamespace(), $wgPageDisqusNamespaceWhitelist ) ) {
			return true;
		}

		$categories = $skin->getTitle()->getParentCategories();
		foreach ( $categories as $key => $value ) {
			$category = substr( $key, strpos( $key, ':' ) + 1 );
			if ( in_array( $category, $wgPageDisqusCategoryBlacklist ) ) {
				return true;
			}
		}

		if ( in_array( $skin->getTitle()->getFullText(), $wgPageDisqusPageBlacklist ) ) {
			return true;
		}

		$title = wfMessage( 'pagedisqus-title' );
		$noscript = wfMessage( 'pagedisqus-noscript' );

		$pageURL = $skin->getTitle()->getFullURL();
		$pageID = $skin->getTitle()->getArticleID();

        $data = <<<HTML
<h2>{$title}</h2>
<div id="disqus_thread"></div>
<script>
    var disqus_config = function () {
        this.page.url = "{$pageURL}";
        this.page.identifier = "{$pageID}";
    };
    (function() {
        var d = document, s = d.createElement('script');

        s.src = '//{$wgPageDisqusShortname}.disqus.com/embed.js';

        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="//disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
HTML;
		return true;
	}

	static function onSkinAfterBottomScripts( $skin, &$text ) {
		global $wgPageDisqusShortname, $wgPageDisqusNamespaceWhitelist, $wgPageDisqusPageBlacklist, $mediaWiki;

		if ( empty( $wgPageDisqusShortname ) ) {
			wfWarn( wfMessage( 'pagedisqus-shortname' ) );
			return true;
		}

		if ( $mediaWiki->getAction() !== 'view' ) {
			return true;
		}

		if ( !in_array( $skin->getTitle()->getNamespace(), $wgPageDisqusNamespaceWhitelist ) ) {
			return true;
		}

		if ( in_array( $skin->getTitle()->getFullText(), $wgPageDisqusPageBlacklist ) ) {
			return true;
		}

		$text .= <<<SCRIPT
<script>
	//<![CDATA[
	(function()
	{
	var links = document.getElementsByTagName('a');
	var query = '?';
	for(var i = 0; i < links.length; i++)
	if(links[i].href.indexOf('#disqus_thread') >= 0)
	query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
	document.write('<script charset="utf-8" type="text/javascript" src="//disqus.com/forums/{$wgPageDisqusShortname}/get_num_replies.js' + query + '"></' + 'script>');
	})();
	//]]>
</script>
SCRIPT;
		return true;
	}
}
