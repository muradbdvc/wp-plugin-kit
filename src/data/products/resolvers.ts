/**
 * Internal dependencies.
 */
import actions from './actions';

import { endpoint } from './endpoint';

import { IResponse } from '@/interfaces';

import { prepareItemForSubmit } from './utils';

const resolvers = {
	*getItems( filters: object ) {
		if ( filters === undefined ) {
			filters = {};
		}

		const queryParam = new URLSearchParams(
			filters as URLSearchParams
		).toString();

		const response: IResponse = yield actions.fetchFromAPIUnparsed(
			`${ endpoint }?${ queryParam }`
		);
		let totalPages = 0;
		let totalCount = 0;

		if ( response.headers !== undefined ) {
			totalPages = response.headers.get( 'X-WP-TotalPages' );
			totalCount = response.headers.get( 'X-WP-Total' );
		}

		yield actions.setItems( response.data );
		yield actions.setTotalPages( totalPages );
		yield actions.setTotalItems( totalCount );
		return actions.setIsLoading( false );
	},

	*getCurrentItem( id: number ) {
		yield actions.setIsLoading( true );
		const path = `${ endpoint }/${ id }`;
		const response = yield actions.fetchFromAPI( path );

		if ( response.id ) {
			const data = prepareItemForSubmit( response );

			yield actions.setForm( data );
		}

		return actions.setIsLoading( false );
	},
};

export default resolvers;
