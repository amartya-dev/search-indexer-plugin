import apiFetch from '@wordpress/api-fetch';

const API_BASE = '/search-indexer-plugin/v1';
const INDEX_BASE = API_BASE.concat( '/index' );

export const SearchPluginIndexerAPIs = () => {
	return {
		index: {
			getAllIndexes: async ( indexType ) => {
				return await apiFetch( {
					path: INDEX_BASE.concat( `/list/${ indexType }` ),
					method: 'GET',
				} );
			},
			getIndexSettings: async ( indexType ) => {
				return await apiFetch( {
					path: INDEX_BASE.concat( `/settings/${ indexType }` ),
					method: 'GET',
				} );
			},
			saveIndexSettings: async ( params ) => {
				return await apiFetch( {
					path: INDEX_BASE.concat( '/save' ),
					method: 'POST',
					data: params,
				} );
			},
			getDefaultIndex: async () => {
				return await apiFetch( {
					path: INDEX_BASE.concat( '/default' ),
					method: 'GET',
				} );
			},
			setDefaultIndex: async ( indexType ) => {
				return await apiFetch( {
					path: INDEX_BASE.concat( '/default' ),
					method: 'POST',
					data: {
						index_name: indexType,
					},
				} );
			},
		},
	};
};
