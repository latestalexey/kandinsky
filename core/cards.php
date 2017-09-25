<?php

/** == Posts elements == **/
function knd_post_card( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	$ex = apply_filters( 'knd_the_title', knd_get_post_excerpt( $cpost, 25, true ) );
	?>
<article class="flex-cell flex-md-6 flex-lg-4 tpl-post card">
    <a href="<?php echo $pl; ?>" class="thumbnail-link">
        <div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
        <div class="entry-data">
            <h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
        </div>

        <div class="entry-meta"><?php echo strip_tags(knd_posted_on($cpost), '<span>');?></div>
    </a>
</article>
<?php
}

function knd_related_post_card( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	$ex = apply_filters( 'knd_the_title', knd_get_post_excerpt( $cpost, 40, true ) );
	?>
<article class="flex-cell flex-md-6 tpl-related-post card">
    <a href="<?php echo $pl; ?>" class="entry-link">
        <div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
        <div class="entry-data">
            <h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
        </div>
    <?php if('project' != $cpost->post_type) { ?>
    <div class="entry-meta"><?php echo strip_tags(knd_posted_on($cpost), '<span>');?></div>
    <?php } ?>
    
</a>
</article>
<?php
}

function knd_related_post_link( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	?>
<a href="<?php echo $pl; ?>" class="entry-link"><?php echo get_the_title($cpost);?></h4></a>
<?php
}

/** Projects */
function knd_project_card( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	?>
<article class="flex-cell flex-md-4 tpl-project card">
    <a href="<?php echo $pl; ?>" class="entry-link">
        <div class="entry-preview"><?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?></div>
        <h4 class="entry-title">
            <span><?php echo get_the_title($cpost);?></span>
        </h4>
    </a>
</article>
<?php
}

/* Orgs */
function knd_org_card( WP_Post $cpost ) {
	?>
<article class="tpl-org logo">
    <a href="<?php echo esc_url($cpost->post_excerpt);?>"
        class="logo-link logo-frame" target="_blank"
        title="<?php echo esc_attr($cpost->post_title);?>"> <span><?php echo get_the_post_thumbnail($cpost, 'post-thumbnail');?></span>
    </a>
</article>
<?php
}

/** search **/
function knd_search_card( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	$ex = apply_filters( 'knd_the_title', knd_get_post_excerpt( $cpost, 40, true ) );
	
	?>
<article class="tpl-search">
    <a href="<?php echo $pl; ?>" class="entry-link">
        <h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
        <div class="entry-meta"><?php echo $pl ;?></div>
        <div class="entry-summary"><?php echo $ex;?></div>
    </a>
</article>
<?php
}

/** == Helpers == **/

/** Excerpt **/
function knd_get_post_excerpt( $cpost, $l = 30, $force_l = false ) {
	if ( is_int( $cpost ) )
		$cpost = get_post( $cpost );
	
	$e = ( ! empty( $cpost->post_excerpt ) ) ? $cpost->post_excerpt : wp_trim_words( 
		strip_shortcodes( $cpost->post_content ), 
		$l );
	if ( $force_l )
		$e = wp_trim_words( $e, $l );
	
	return $e;
}

/** Deafult thumbnail for posts **/
function knd_post_thumbnail( $post_id, $size = 'post-thumbnail' ) {
	$thumb = get_the_post_thumbnail( $post_id, $size );
	
	return $thumb;
}

function knd_post_thumbnail_src( $post_id, $size = 'post-thumbnail' ) {
	$src = get_the_post_thumbnail_url( $post_id, $size );
	
	return $src;
}

function knd_single_post_thumbnail( $post_id, $size = 'post-thumbnail', $post_format = 'standard' ) {
	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( ! $thumb_id )
		return;
	
	$thumb = get_post( $thumb_id );
	$cap = ( ! empty( $thumb->post_excerpt ) ) ? $thumb->post_excerpt : get_post_meta( 
		$thumb_id, 
		'_wp_attachment_image_alt', 
		true ); // to_do: make this real
	
	if ( $post_format == 'standard' ) {
		?>
<figure class="wp-caption alignnone">
		<?php echo wp_get_attachment_image($thumb_id, $size);?>
		<?php if(!empty($cap)) { ?>
			<figcaption class="wp-caption-text"><?php echo apply_filters('knd_the_title', $cap);?></figcaption>
		<?php } ?>
	</figure>
<?php
	} elseif ( $post_format == 'introimg' ) {
		?>
<figure class="introimg-figure">
    <div class="introimg">
        <div class="tpl-pictured-bg" style="background-image: url(<?php echo get_the_post_thumbnail_url($post_id, $size);?>);" ></div>
    </div>
		<?php if(!empty($cap)) { ?>
			<figcaption class="wp-caption-text"><?php echo apply_filters('knd_the_title', $cap);?></figcaption>
		<?php } ?>
	</figure>
<?php
	}

}


