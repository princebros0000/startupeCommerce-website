<?php

if ( !function_exists( 'porto_get_related_posts' ) ) :
    function porto_get_related_posts($post_id) {
        global $porto_settings;

        $args = '';
        $args = wp_parse_args($args, array(
            'showposts' => $porto_settings['post-related-count'],
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => 0,
            'category__in' => wp_get_post_categories($post_id),
            'orderby' => $porto_settings['post-related-orderby']
        ));

        $query = new WP_Query($args);

        return $query;
    }
endif;

if ( !function_exists( 'porto_get_related_portfolios' ) ) :
    function porto_get_related_portfolios($post_id) {
        global $porto_settings;

        $args = '';

        $item_cats = get_the_terms($post_id, 'portfolio_cat');
        $item_array = array();
        if ($item_cats) :
            foreach($item_cats as $item_cat) {
                $item_array[] = $item_cat->term_id;
            }
        endif;

        $args = wp_parse_args($args, array(
            'showposts' => $porto_settings['portfolio-related-count'],
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => 0,
            'post_type' => 'portfolio',
            'tax_query' => array(
                array(
                    'taxonomy' => 'portfolio_cat',
                    'field' => 'id',
                    'terms' => $item_array
                )
            ),
            'orderby' => $porto_settings['portfolio-related-orderby']
        ));

        $query = new WP_Query($args);

        return $query;
    }
endif;

if ( !function_exists( 'porto_get_related_members' ) ) :
    function porto_get_related_members($post_id) {
        global $porto_settings;

        $args = '';

        $item_cats = get_the_terms($post_id, 'member_cat');
        $item_array = array();
        if ($item_cats) :
            foreach($item_cats as $item_cat) {
                $item_array[] = $item_cat->term_id;
            }
        endif;

        $args = wp_parse_args($args, array(
            'showposts' => $porto_settings['member-related-count'],
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => 0,
            'post_type' => 'member',
            'tax_query' => array(
                array(
                    'taxonomy' => 'member_cat',
                    'field' => 'id',
                    'terms' => $item_array
                )
            ),
            'orderby' => $porto_settings['member-related-orderby']
        ));

        $query = new WP_Query($args);

        return $query;
    }
endif;

if ( !function_exists( 'porto_get_portfolios_by_ids' ) ) :
    function porto_get_portfolios_by_ids($ids) {
        $args = '';
        $ids = explode(',', $ids);
        $ids = array_map('trim', $ids);

        $args = wp_parse_args($args, array(
            'post_type'    => 'portfolio',
            'post_status' => 'publish',
            'ignore_sticky_posts'    => 1,
            'posts_per_page' => -1,
            'post__in' => $ids
        ));

        $query = new WP_Query($args);

        return $query;
    }
endif;

if ( !function_exists( 'porto_get_posts_by_ids' ) ) :
    function porto_get_posts_by_ids($ids) {
        $args = '';
        $ids = explode(',', $ids);
        $ids = array_map('trim', $ids);

        $args = wp_parse_args($args, array(
            'post_type'    => 'post',
            'post_status' => 'publish',
            'ignore_sticky_posts'    => 1,
            'posts_per_page' => -1,
            'post__in' => $ids
        ));

        $query = new WP_Query($args);

        return $query;
    }
endif;

if ( !function_exists( 'porto_get_excerpt' ) ) :
    function porto_get_excerpt($limit = 45, $more_link = true, $more_style_block = false) {
        global $porto_settings;

        if (!$limit) {
            $limit = 45;
        }

        if (has_excerpt()) {
            $content = get_the_excerpt();
        } else {
            $content = get_the_content();
        }

        $pattern = '/\[vc_custom_heading(.+?)?\](?:(.+?)?\[\/vc_custom_heading\])?/';
        $content = preg_replace($pattern,'',$content);
        
        $content = porto_strip_tags( apply_filters( 'the_content', $content ) );
        $content = explode(' ', $content, $limit);

        if (count($content) >= $limit) {
            array_pop($content);
            if ($more_link)
                $content = implode(" ",$content).'... ';
            else
                $content = implode(" ",$content).' [...]';
        } else {
            $content = implode(" ",$content);
        }

        if ($porto_settings['blog-excerpt-type'] == 'html') {
            $content = apply_filters('the_content', $content);
            $content = do_shortcode($content);
        }

        if ($more_link) {
            if ($more_style_block) {
                $content .= ' <a class="read-more read-more-block" href="'.esc_url( apply_filters( 'the_permalink', get_permalink() ) ).'">'.__('Read More', 'porto').' <i class="fa fa-long-arrow-right"></i></a>';
            } else {
                $content .= ' <a class="read-more" href="'.esc_url( apply_filters( 'the_permalink', get_permalink() ) ).'">'.__('read more', 'porto').' <i class="fa fa-angle-right"></i></a>';
            }
        }

        if ($porto_settings['blog-excerpt-type'] != 'html') {
            $content = '<p class="post-excerpt">'.$content.'</p>';
        }

        return $content;
    }
endif;

if ( !function_exists( 'porto_get_attachment' ) ) :
    function porto_get_attachment( $attachment_id, $size = 'full', $force_resize = false ) {
        if (!$attachment_id)
            return false;
        $attachment = get_post( $attachment_id );
        if (!$force_resize) {
            $image = wp_get_attachment_image_src($attachment_id, $size);
        } else {
            $image = porto_image_resize($attachment_id, $size);
            if (!$image)
                return false;
        }

        if (!$attachment)
            return false;

        return array(
            'alt' => esc_attr(get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true )),
            'caption' => esc_attr($attachment->post_excerpt),
            'description' => force_balance_tags($attachment->post_content),
            'href' => get_permalink( $attachment->ID ),
            'src' => esc_url($image[0]),
            'title' => esc_attr($attachment->post_title),
            'width' => esc_attr($image[1]),
            'height' => esc_attr($image[2])
        );
    }
endif;

if ( !function_exists( 'porto_comment' ) ) :
    function porto_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment; ?>

    <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

        <div class="comment-body">
            <div class="img-thumbnail">
                <?php echo get_avatar($comment, 80); ?>
            </div>
            <div class="comment-block">
                <div class="comment-arrow"></div>
                <span class="comment-by">
                    <strong><?php echo get_comment_author_link() ?></strong>
                    <span class="pt-right">
                        <span> <?php edit_comment_link('<i class="fa fa-pencil"></i> ' . __('Edit', 'porto'),'  ','') ?></span>
                        <span> <?php comment_reply_link(array_merge( $args, array('reply_text' => '<i class="fa fa-reply"></i> ' . __('Reply', 'porto'), 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
                    </span>
                </span>
                <div>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em><?php echo __('Your comment is awaiting moderation.', 'porto') ?></em>
                        <br />
                    <?php endif; ?>
                    <?php comment_text() ?>
                </div>
                <span class="date pt-right"><?php printf(__('%1$s at %2$s', 'porto'), get_comment_date(),  get_comment_time()) ?></span>
            </div>
        </div>

    <?php }
endif;

if ( !function_exists( 'porto_post_date' ) ) :
    function porto_post_date() {
        ?>
        <span class="day"><?php echo get_the_time('d', get_the_ID()); ?></span>
        <span class="month"><?php echo get_the_time('M', get_the_ID()); ?></span>
        <?php
    }
endif;

if ( !function_exists( 'porto_post_format' ) ) :
    function porto_post_format() {
        global $porto_settings;

        $post = get_post();
        $post_format = get_post_format();
        if ($porto_settings['post-format'] && $post_format) {
            $ext_link = '';
            if ($post_format == 'link') {
                $ext_link = get_post_meta($post->ID, 'external_url', true);
                if ($ext_link) : ?>
                    <a href="<?php echo esc_url($ext_link) ?>">
                <?php
                endif;
            }
            if ($post_format) : ?>
                <div class="format <?php echo $post_format ?>">
                    <i class="fa fa-<?php
                    switch ($post_format) {
                        case 'aside': echo 'file-text'; break;
                        case 'gallery': echo 'camera-retro'; break;
                        case 'link': echo 'link'; break;
                        case 'image': echo 'picture-o'; break;
                        case 'quote': echo 'quote-left'; break;
                        case 'video': echo 'film'; break;
                        case 'audio': echo 'music'; break;
                        case 'chat': echo 'comments'; break;
                        case 'status': echo 'exclamation-triangle'; break;
                    }
                    ?>"></i>
                </div>
            <?php endif;
            if ($ext_link) echo '</a>';
        }

        if ( is_sticky() && is_home() && ! is_paged() ) {
            printf( '<span class="sticky">%s</span>', ((isset($porto_settings['hot-label']) && $porto_settings['hot-label']) ? $porto_settings['hot-label'] : __('HOT', 'porto')) );
        }
    }
endif;

if ( !function_exists( 'porto_pagination' ) ) :
    function porto_pagination($max_num_pages = null, $load_more = false) {
        global $wp_query, $wp_rewrite;

        $max_num_pages = ($max_num_pages) ? $max_num_pages : $wp_query->max_num_pages;

        // Don't print empty markup if there's only one page.
        if ( $max_num_pages < 2 ) {
            return;
        }

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
        $page_num_link = html_entity_decode( get_pagenum_link() );
        $query_args   = array();
        $url_parts    = explode( '?', $page_num_link );

        if ( isset( $url_parts[1] ) ) {
            wp_parse_str( $url_parts[1], $query_args );
        }

        $page_num_link = esc_url( remove_query_arg( array_keys( $query_args ), $page_num_link ) );
        $page_num_link = trailingslashit( $page_num_link ) . '%_%';

        $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $page_num_link, 'index.php' ) ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

        $next_text = ( $load_more ) ? __( 'Load More', 'porto' ) : __( 'Next&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i>', 'porto' );

        // Set up paginated links.
        $links = paginate_links( array(
            'base'     => $page_num_link,
            'format'   => $format,
            'total'    => $max_num_pages,
            'current'  => $paged,
            'end_size' => 1,
            'mid_size' => 1,
            'add_args' => array_map( 'urlencode', $query_args ),
            'prev_text' => __( '<i class="fa fa-long-arrow-left"></i>&nbsp;&nbsp;Prev', 'porto' ),
            'next_text' => $next_text,
        ) );

        if ( $links ) :
            ?>
            <div class="clearfix"></div>
            <div class="pagination-wrap<?php if( $load_more ) { echo " load-more"; }?>">
                <?php if( $load_more ) : ?>
                <div class="bounce-loader">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
                <?php endif; ?>
                <div class="pagination<?php if( $load_more ) { echo " load-more"; }?>" role="navigation">
                    <?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $links) ?>
                </div>
            </div>
        <?php
        endif;
    }
endif;

add_filter('comments_popup_link_attributes', 'porto_add_comment_hash_scroll');
function porto_add_comment_hash_scroll($attributes) {
    if (is_single())
        $attributes .= ' class="hash-scroll"';
    return $attributes;
}

add_filter('comment_form_defaults', 'porto_comment_form_defaults');
function porto_comment_form_defaults($defaults) {
    global $porto_settings;

    if ($porto_settings['post-title-style'] == 'without-icon') {
        $defaults['title_reply_before'] = '<h4 id="reply-title" class="comment-reply-title">';
        $defaults['title_reply_after'] = '</h4>';
    }

    if ( is_singular( 'post' ) ) {
        $post_layout = get_post_meta(get_the_ID(), 'post_layout', true);
        $post_layout = ($post_layout == 'default' || !$post_layout) ? $porto_settings['post-content-layout'] : $post_layout;
        if ( 'woocommerce' === $post_layout ) {

            $defaults['title_reply'] = esc_html__( 'LEAVE A COMMENT', 'porto' );
            $defaults['comment_field'] = '<div id="comment-textarea" class="form-group mb20"><textarea id="comment" name="comment" rows="5" aria-required="true" class="form-control" placeholder="' . esc_html__( 'Message', 'porto' ) . '*"></textarea></div>';
            $defaults['comment_notes_before'] = '<p class="comment-notes">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'porto' ) . '</p>';
            $defaults['id_submit'] = 'comment-submit';
            $defaults['class_submit'] = 'btn btn-accent min-width';
            $defaults['fields'] = apply_filters( 'comment_form_default_fields', array(
                'author' => '<div class="col-md-4 form-group"><input name="author" type="text" class="form-control" value="" placeholder="' . esc_html__( 'Name', 'porto' ) . '*"> </div>',
                'email' => '<div class="col-md-4 form-group"><input name="email" type="text" class="form-control" value="" placeholder="' . esc_html__( 'Email', 'porto' ) . '*"> </div>',
                'subject' => '<div class="col-md-4 form-group"><input name="subject" type="text" class="form-control" value="" placeholder="' . esc_html__( 'Subject', 'porto' ) . '"> </div>'
            ) );
        }
    }

    return $defaults;
}

add_action( 'comment_form_before_fields', 'porto_comment_form_before_fields' );
add_action( 'comment_form_after_fields', 'porto_comment_form_after_fields' );
if ( ! function_exists( 'porto_comment_form_before_fields' ) ) :
    function porto_comment_form_before_fields( $fields ) {
        if ( is_singular( 'post' ) ) {
            global $porto_settings;
            $post_layout = get_post_meta(get_the_ID(), 'post_layout', true);
            $post_layout = ($post_layout == 'default' || !$post_layout) ? $porto_settings['post-content-layout'] : $post_layout;
            if ( 'woocommerce' === $post_layout ) {
                echo '<div class="row">';
            }
        }
    }
endif;
if ( ! function_exists( 'porto_comment_form_after_fields' ) ) :
    function porto_comment_form_after_fields( $fields ) {
        if ( is_singular( 'post' ) ) {
            global $porto_settings;
            $post_layout = get_post_meta(get_the_ID(), 'post_layout', true);
            $post_layout = ($post_layout == 'default' || !$post_layout) ? $porto_settings['post-content-layout'] : $post_layout;
            if ( 'woocommerce' === $post_layout ) {
                echo '</div>';
            }
        }
    }
endif;