<?php

class PageDisqus {

	static function onSkinAfterContent( &$data, $skin )  {
		global $wgPageDisqusShortname, $wgPageDisqusExclude, $wgTitle, $wgRequest;

		$wgPageDisqusShortname = strtolower( $wgPageDisqusShortname );

		if ( empty( $wgPageDisqusShortname ) ) {
			$shortname = wfMessage( 'pagedisqus-shortname' )->text();
			exit( $shortname );
		}

		if ( $wgRequest->getVal( 'action', 'view' ) != 'view' ) {
			return true;
		}

		if ( ! empty( $wgPageDisqusExclude ) ) {
			foreach ( $wgPageDisqusExclude as $excluded ) {
				if ( preg_match( '/' . $excluded . '/', $wgTitle->getFullText() ) ) {
					return true;
				}
			}
		}

		$title = wfMessage( 'pagedisqus-title' )->text();
		$noscript = wfMessage( 'pagedisqus-noscript' )->text();

		$data = $title . '
			<script type="text/javascript">
			var disqus_developer = 0;
			var disqus_url = "' . $wgRequest->getFullRequestURL() . '";
			var disqus_title = "' . $wgTitle->getText() . '";
			</script>
			<br />
			<div id="disqus_thread"></div>
			<script type="text/javascript" src="http://disqus.com/forums/' . $wgPageDisqusShortname . '/embed.js"></script>
			<noscript><a href="http://disqus.com/forums/' . $wgPageDisqusShortname . '/?url=ref">' . $noscript . '</a></noscript>';
		return true;
	}

	static function onSkinAfterBottomScripts( $skin, &$text ) {
		global $wgPageDisqusShortname, $wgPageDisqusExclude, $wgTitle, $wgRequest;

		if ( $wgRequest->getVal( 'action', 'view' ) != 'view' ) {
			return true;
		}

		if ( ! empty( $wgPageDisqusExclude ) ) {
			foreach ( $wgPageDisqusExclude as $excluded ) {
				if ( preg_match( '/' . $excluded . '/', $wgTitle->getFullText() ) ) {
					return true;
				}
			}
		}

		if ( empty( $wgPageDisqusShortname ) ) {
			$shortname = wfMessage( 'pagedisqus-shortname' )->text();
			exit( $shortname );
		}

		$text .= "<script type=\"text/javascript\">
			//<![CDATA[
			(function()
			{
			var links = document.getElementsByTagName('a');
			var query = '?';
			for(var i = 0; i < links.length; i++)
			if(links[i].href.indexOf('#disqus_thread') >= 0)
			query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
			document.write('<script charset=\"utf-8\" type=\"text/javascript\" src=\"http://disqus.com/forums/" . $wgPageDisqusShortname . "/get_num_replies.js' + query + '\"></' + 'script>');
			})();
			//]]>
			</script>";
		return true;
	}
}
