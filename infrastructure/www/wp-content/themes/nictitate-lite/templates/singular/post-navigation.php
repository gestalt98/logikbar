<div class="pagination clearfix">
	<?php 
	$args = array(
		'prev_text' => '<p class="prev-post">
		<span>&laquo;&nbsp;' . __('Previous Article', 'nictitate-lite') .'</span><br/>
		%title
		</p>',
		'next_text' => '<p class="next-post">
		<span>' . __('Next Article', 'nictitate-lite') .'&nbsp;&raquo;</span><br/>
		%title
		</p>',
	);

	the_post_navigation( $args );

	?>
</div>