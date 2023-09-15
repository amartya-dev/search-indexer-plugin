import { useEffect, useState } from '@wordpress/element';
import { TextInput } from './common/TextInput';
import { PasswordInput } from './common/PasswordInput';
import { LoadingButton } from './common/LoadingButton';
import { apiCall } from '../utils/apiCall';
import { SearchPluginIndexerAPIs } from '../utils/api';

const indexType = 'meilisearch';

export const MeiliSearchSettings = () => {
	const [ connectionDetails, setConnectionDetails ] = useState( {
		host: '',
		apiKey: '',
	} );
	const [ indexName, setIndexName ] = useState( '' );
	const [ saveLoading, setSaveLoading ] = useState( false );
	const [ makeDefaultLoading, setMakeDefaultLoading ] = useState( false );
	const [ reIndexLoading, setReIndexLoading ] = useState( false );
	const [ isDefault, setIsDefault ] = useState( false );
	const [ availableIndices, setAvailableIndices ] = useState( [] );

	const getIndexSettings = async () => {
		const response = await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.getIndexSettings,
			apiCallParams: indexType,
		} );
		if (
			! response.failed &&
			Object.keys( response.settings ).length !== 0
		) {
			setConnectionDetails( response.settings.connection );
			setIndexName( response.settings.index_name );
		}
	};
	const getIsDefault = async () => {
		const response = await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.getDefaultIndex,
		} );
		setIsDefault( response.default && response.default === indexType );
	};
	const getAllIndexes = async () => {
		const response = await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.getAllIndexes,
			apiCallParams: indexType,
		} );
		if ( ! response.failed ) {
			setAvailableIndices( response.indexes );
		}
	};
	useEffect( () => {
		getIndexSettings();
		getIsDefault();
		getAllIndexes();
	}, [] );

	const updateIndexSettings = async () => {
		setSaveLoading( true );
		await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.saveIndexSettings,
			apiCallParams: {
				index_name: indexType,
				settings: {
					index_name: indexName,
					connection: connectionDetails,
				},
			},
		} );
		setSaveLoading( false );
		window.location.reload();
	};

	const makeDefault = async () => {
		setMakeDefaultLoading( true );
		await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.setDefaultIndex,
			apiCallParams: indexType,
		} );
		setMakeDefaultLoading( false );
		await getIsDefault();
		window.location.reload();
	};

	const reIndex = async () => {
		setReIndexLoading( true );
		await apiCall( {
			apiCallFunc: SearchPluginIndexerAPIs().index.reIndex,
			apiCallParams: indexType,
		} );
		setReIndexLoading( false );
		window.location.reload();
	};

	return (
		<div className="flex justify-center items-center mt-8">
			<div className="w-5/6">
				{ /* Connection Settings */ }
				<h2 className="text-2xl mb-4">Connection Settings</h2>
				<div className="mb-6">
					<TextInput
						id="typesense-host-input"
						label="Host"
						value={ connectionDetails.host }
						onChange={ ( event ) => {
							setConnectionDetails( {
								...connectionDetails,
								host: event.target.value,
							} );
						} }
					/>
				</div>
				<div className="mb-6">
					<PasswordInput
						id="typesense-api-key-input"
						label="Api Key"
						value={ connectionDetails.apiKey }
						onChange={ ( event ) => {
							setConnectionDetails( {
								...connectionDetails,
								apiKey: event.target.value,
							} );
						} }
					/>
				</div>
				<h2 className="text-2xl mb-4">Index specific settings</h2>
				<TextInput
					id="typesense-index-input"
					label="Index Name"
					value={ indexName }
					onChange={ ( event ) => {
						setIndexName( event.target.value );
					} }
				/>
				{ availableIndices.length >= 0 && (
					<>
						<h2 className="text-2xl mt-4">Available Indices</h2>
						<ul className="list-disc ml-4">
							{ availableIndices.map( ( availableIndex ) => {
								return (
									<li
										className="text-md"
										key={ availableIndex.uid }
									>
										{ 'Name: '.concat(
											availableIndex.uid
										) }
									</li>
								);
							} ) }
						</ul>
					</>
				) }
				<div className="mt-8">
					<LoadingButton
						loading={ saveLoading }
						onSubmit={ updateIndexSettings }
					>
						Save
					</LoadingButton>
					<LoadingButton
						loading={ reIndexLoading }
						onSubmit={ reIndex }
					>
						Re-Index
					</LoadingButton>
					{ ! isDefault && (
						<LoadingButton
							loading={ makeDefaultLoading }
							onSubmit={ makeDefault }
						>
							Make Default
						</LoadingButton>
					) }
				</div>
			</div>
		</div>
	);
};
