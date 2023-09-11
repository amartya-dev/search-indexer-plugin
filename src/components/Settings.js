import { useState } from '@wordpress/element';
import { TypeSenseSettings } from './TypeSenseSettings';

const IndexTabs = () => {
	const tabs = {
		typesense: {
			name: 'Type Sense',
			component: <TypeSenseSettings />,
		},
		meili: {
			name: 'Meili Search',
			component: <></>,
		},
		algolia: {
			name: 'Algolia',
			component: <></>,
		},
	};
	const [ activeTab, setActiveTab ] = useState( 'typesense' );

	return (
		<>
			<ul className="tab-container">
				{ Object.keys( tabs ).map( ( tab ) => {
					return (
						<li
							key={ tab }
							className={
								tab === activeTab
									? 'tab-item-active'
									: 'tab-item'
							}
						>
							<button
								onClick={ () => {
									setActiveTab( tab );
								} }
							>
								{ tabs[ tab ].name }
							</button>
						</li>
					);
				} ) }
			</ul>
			{ tabs[ activeTab ].component }
		</>
	);
};

export const Settings = () => {
	return (
		<div className="m-2">
			<h2 className="text-3xl pt-8 ml-3 mb-4">Configure Indexes</h2>
			<IndexTabs />
		</div>
	);
};
