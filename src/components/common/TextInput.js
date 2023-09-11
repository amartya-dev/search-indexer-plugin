export const TextInput = ( {
	id,
	label,
	helperText,
	value,
	onChange,
	error = false,
} ) => {
	return (
		<div>
			<label htmlFor={ id } className="text-input-label">
				{ label }
			</label>
			<input
				id={ id }
				type="text"
				className="text-input"
				placeholder=" "
				value={ value }
				onChange={ onChange }
			/>
			<p className={ `text-md ${ error ? 'text-red-600' : '' }` }>
				{ helperText }
			</p>
		</div>
	);
};
