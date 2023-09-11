import { useState } from '@wordpress/element';
import { ReactComponent as VisibleIcon } from '../../icons/VisibleIcon.svg';
import { ReactComponent as NonVisibleIcon } from '../../icons/NonVisibleIcon.svg';

export const PasswordInput = ( {
	id,
	label,
	helperText,
	value,
	onChange,
	error = false,
} ) => {
	const [ passwordVisible, togglePasswordVisible ] = useState( false );

	return (
		<div>
			<label htmlFor={ id } className="text-input-label">
				{ label }
			</label>
			<div className="relative">
				<input
					id={ id }
					className="text-input"
					placeholder=" "
					value={ value }
					onChange={ onChange }
					type={ passwordVisible ? 'text' : 'password' }
				/>
				<button
					className="text-black absolute right-2.5 bottom-[0.2rem] font-medium rounded-lg text-sm px-4 py-2"
					onClick={ () => {
						togglePasswordVisible( ! passwordVisible );
					} }
				>
					{ passwordVisible ? <NonVisibleIcon /> : <VisibleIcon /> }
				</button>
			</div>
			<p className={ `text-md ${ error ? 'text-red-600' : '' }` }>
				{ helperText }
			</p>
		</div>
	);
};
