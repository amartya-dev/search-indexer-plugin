export const SelectInput = ( {
	id,
	label,
	helperText,
	value,
	onChange,
	options,
	error = false,
} ) => {
	return (
		<div>
			<label htmlFor={ id } className="select-input-label">
				{ label }
			</label>
			<select
				id={ id }
				type="select"
				className="select-input"
				placeholder=" "
				value={ value }
				onChange={ onChange }
			>
				{ options.map( ( option ) => {
					return (
						<option
							key={ option.label }
							selected={ option.value === value }
						>
							{ option.label }
						</option>
					);
				} ) }
			</select>
			<p className={ `text-md ${ error ? 'text-red-600' : '' }` }>
				{ helperText }
			</p>
		</div>
	);
};
