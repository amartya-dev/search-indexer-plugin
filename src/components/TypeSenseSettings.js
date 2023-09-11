import { useEffect, useState } from '@wordpress/element';
import { TextInput } from './common/TextInput';
import { PasswordInput } from './common/PasswordInput';
import { LoadingButton } from './common/LoadingButton';
import { apiCall } from '../utils/apiCall';
import { SearchPluginIndexerAPIs } from '../utils/api';

const indexType = 'typesense';

export const TypeSenseSettings = () => {
	const [ connectionDetails, setConnectionDetails ] = useState( {
		host: '',
		port: '',
		protocol: '',
		apiKey: '',
	} );
	const [ indexName, setIndexName ] = useState( '' );
	const [ saveLoading, setSaveLoading ] = useState( false );
	const [ makeDefaultLoading, setMakeDefaultLoading ] = useState( false );
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

	return (
		<div className="flex justify-center items-center mt-8">
			<div className="w-5/6">
				{ /* Connection Settings */ }
				<h2 className="text-2xl mb-4">Connection Settings</h2>
				<div className="grid gap-6 mb-4 grid-cols-3">
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
					<TextInput
						id="typesense-port-input"
						label="Port"
						value={ connectionDetails.port }
						onChange={ ( event ) => {
							setConnectionDetails( {
								...connectionDetails,
								port: event.target.value,
							} );
						} }
					/>
					<TextInput
						id="typesense-protocol-input"
						label="Protocol"
						value={ connectionDetails.protocol }
						onChange={ ( event ) => {
							setConnectionDetails( {
								...connectionDetails,
								protocol: event.target.value,
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
										key={ availableIndex.name }
									>
										{ 'Name: '.concat(
											availableIndex.name,
											' Documents: ',
											availableIndex.num_documents
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
					<LoadingButton loading={ false }>Re-Index</LoadingButton>
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
