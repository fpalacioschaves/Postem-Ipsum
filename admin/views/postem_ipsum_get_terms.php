<th scope="row"><?php _e( 'Select Term/Category', POSTEM_IPSUM_TEXT_DOMAIN ); ?></th>
<td>
	<select name="postem_ipsum_term" id="postem_ipsum_term">
		<option value="0" selected>Select a term</option>
		<?php foreach ( $postem_ipsum_terms as $term ): ?>
			<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		<?php endforeach; ?>
	</select>
</td>
