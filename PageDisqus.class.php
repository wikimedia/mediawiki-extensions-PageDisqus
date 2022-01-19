<?php

class PageDisqus {

	static function onSkinAfterContent( &$data, $skin )  {
		global $wgPageDisqusShortname,
			$wgPageDisqusNamespaceBlacklist,
			$wgPageDisqusNamespaceWhitelist,
			$wgPageDisqusCategoryBlacklist,
			$wgPageDisqusCategoryWhitelist,
			$wgPageDisqusPageBlacklist,
			$wgPageDisqusPageWhitelist;

		if ( empty( $wgPageDisqusShortname ) ) {
			$data = Html::rawElement( 'span', [ 'class' => 'error' ], wfMessage( 'pagedisqus-shortname' ) );
			return true;
		}

		if ( Action::getActionName( $skin->getContext() ) !== 'view' ) {
			return true;
		}

		if ( in_array( $skin->getTitle()->getNamespace(), $wgPageDisqusNamespaceBlacklist ) ) {
			return true;
		}
		if ( $wgPageDisqusNamespaceWhitelist && ! in_array( $skin->getTitle()->getNamespace(), $wgPageDisqusNamespaceWhitelist ) ) {
			return true;
		}

		$ok = $wgPageDisqusCategoryWhitelist ? false : true;
		$categories = array_keys( $skin->getTitle()->getParentCategories() );
		foreach ( $categories as $category ) {
			$category = substr( $category, strpos( $category, ':' ) + 1 );
			$category = str_replace( '_', ' ', $category );
			if ( in_array( $category, $wgPageDisqusCategoryBlacklist ) ) {
				return true;
			}
			if ( in_array( $category, $wgPageDisqusCategoryWhitelist ) ) {
				$ok = true;
			}
		}
		if ( ! $ok ) {
			return true;
		}

		if ( in_array( $skin->getTitle()->getFullText(), $wgPageDisqusPageBlacklist ) ) {
			return true;
		}
		if ( $wgPageDisqusPageWhitelist && ! in_array( $skin->getTitle()->getFullText(), $wgPageDisqusPageWhitelist ) ) {
			return true;
		}
		
		$title = wfMessage( 'pagedisqus-title' );
		$noscript = wfMessage( 'pagedisqus-noscript' );

		$pageURL = $skin->getTitle()->getFullURL();
		$pageID = $skin->getTitle()->getArticleID();

        $data = <<<HTML
<h2 id="disqus_title">{$title}</h2>
<div id="disqus_thread"></div>
<script>
    var disqus_config = function () {
    this.page.url = "{$pageURL}";
    this.page.identifier = "{$pageID}";
    };
    (function() {
    var d = document, s = d.createElement('script');
    s.src = 'https://{$wgPageDisqusShortname}.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
</script><noscript>{$noscript}</noscript>
HTML;
		return true;
	}
}
