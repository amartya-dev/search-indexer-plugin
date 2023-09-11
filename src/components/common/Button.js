export const Button = ( { buttonText, onClick } ) => {
	return (
		<button type="button" className="button" onClick={ onClick }>
			{ buttonText }
		</button>
	);
};
