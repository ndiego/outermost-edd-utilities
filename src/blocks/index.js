/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

import * as userInfo from './user-info';

const allBlocks = [
	userInfo
];

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 *
 */
const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}
	const { metadata, settings, name } = block;
	registerBlockType( { name, ...metadata }, settings );
};

// Register all blocks.
allBlocks.forEach( registerBlock );
