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

		$page_url = $wgRequest->getFullRequestURL();
		$page_identifier = $wgPageDisqusShortname;

        $data = <<<HTML
<br><hr>
{$title}
<div id="disqus_thread"></div>
<script>
    var disqus_config = function () {
        this.page.url = "{$page_url}";
        this.page.identifier = "{$page_identifier}";
    };
    (function() {
        var d = document, s = d.createElement('script');

        s.src = '//{$page_identifier}.disqus.com/embed.js';

        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="//disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
HTML;


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
			document.write('<script charset=\"utf-8\" type=\"text/javascript\" src=\"//disqus.com/forums/" . $wgPageDisqusShortname . "/get_num_replies.js' + query + '\"></' + 'script>');
			})();
			//]]>
			</script>";
		return true;
	}
}
