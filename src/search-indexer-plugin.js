import domReady from '@wordpress/dom-ready';
import { createRoot, render } from '@wordpress/element';

import './styles/search-indexer-plugin.css';
import App from './App';

const SEARCH_INDEXER_PLUGIN_PAGE_ROOT_ELEMENT = 'sip-app';

const RenderSearchIndexerPlugin = () => {
	const DOM_ELEMENT = document.getElementById(
		SEARCH_INDEXER_PLUGIN_PAGE_ROOT_ELEMENT
	);

	if ( null !== DOM_ELEMENT ) {
		if ( 'undefined' !== typeof createRoot ) {
			// WP 6.2+ only
			createRoot( DOM_ELEMENT ).render( <App /> );
		} else if ( 'undefined' !== typeof render ) {
			render( <App />, DOM_ELEMENT );
		}
	}
};

domReady( RenderSearchIndexerPlugin );
