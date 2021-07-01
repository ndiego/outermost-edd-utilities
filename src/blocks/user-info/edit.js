/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Placeholder } from '@wordpress/components';
import { info } from '@wordpress/icons';
import { LinkControl, InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	ToolbarButton,
	ToggleControl,
	Popover,
	DateTimePicker,
	PanelBody,
	CustomSelectControl,
	TextControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {
	const { attributes, setAttributes } = props;
	const { imageLink, userNameLink } = attributes;
	const blockProps = useBlockProps();
	//console.log( attributes );
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Settings', 'outermost-edd-utilities' ) }>
					<TextControl
						label={ __( 'Image Link', 'outermost-edd-utilities' ) }
						value={ imageLink }
						onChange={ ( value ) =>
							setAttributes( { imageLink: value } )
						}
					/>
					<TextControl
						label={ __( 'User Name Link', 'outermost-edd-utilities' ) }
						value={ userNameLink }
						onChange={ ( value ) =>
							setAttributes( { userNameLink: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<Placeholder
					icon={ info }
					label={ __( 'User Info', 'outermost-edd-utilities' ) }
					instructions={ __(
						"This block will render the current user's information on the frontend.",
						'outermost-edd-utilities'
					) }
				/>
			</div>
		</>
	);
}
