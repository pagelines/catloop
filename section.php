<?php
/*
=======
Section: CatLoop
Author: Anthalis
Author URI: http://anthalis.me/
Version: 1.0.0
Description: An easy to use drag & drop category loop
Long: Pull it in the content area of a page template to convert it into a custom category page! Supports pagination, offset, custom no. of posts per page. 
Class Name: CatLoop
Workswith: templates, main, header, morefoot, content
Cloning: true
*/	
class CatLoop extends PageLinesSection {

	var $clone_id = 'catloop_meta';

function before_section_template( $location = '' ) {

		if (ploption('blog_layout_mode', $this->oset ) == 'magazine') {
			$this->wrapper_classes[] = 'multi-post';
		}
		else {
			$this->wrapper_classes[] = 'single-post';
		}
	}	


	function section_optionator( $clone_id ){
		$settings = wp_parse_args( $clone_id, $this->optionator_default );
			$page_metatab_array = array(
						'cloop_headings' => array(
							'title' => __('CatLoop Title', 'catloop'),
							'type'	=> 'multi_option',
							'selectvalues'	=> array(
									'catloop_heading' => array(
											'inputlabel' => __( 'Clone Heading', 'catloop'),
											'type'=> 'text',
											'size'=> 'big',
											),
									'catloop_subhead' => array(
											'inputlabel' => __( 'Clone Description', 'catloop'),
											'type'=> 'textarea',
											),
									'head_alignment' => array(
											'inputlabel' => __( 'Alignment', 'catloop'),
											'type' => 'select',
											'selectvalues'			=> array(
												'left' 		=> array('name' => __( 'Left (Default)', 'catloop' ) ),
												'center' 	=> array('name' => __( 'Center', 'catloop' ) ),
												'right'		=> array('name' => __( 'Right', 'catloop' ) )
												),
											)
										)
									),
						'catloop_regposts_opt' => array(
							'type'		=> 'multi_option',
							'title'		=> __('CatLoop Setup', 'catloop'),
							'shortexp'		=> __('Select the category you want to display in your CatLoop, the excluded category and the order of posts.', 'catloop'),
							'exp' 		=> __('To select multiple categories keep the Cmd / Ctrl key pressed and click.<br />To remove the selection of a category, hold down Ctrl /Cmd and click on the category to be removed from your selection. <br />To Navigate up and down the list, select a category - or multiple categories from the currently visible ones, then use the up/down arrows.<br /><strong>Remember to hold down the Ctrl / Cmd key to select additional categories or to remove selections.</strong> ', 'catloop'),
							'selectvalues'	=> array(
									'categs' => array(
										'type'			=> 'select',
										'selectvalues'			=> $this->get_categs(),
										'inputlabel'			=> __( 'Select category to include', 'catloop')
				 						),
									'catloop_exclude' => array(
										'type'			=> 'select',
										'selectvalues'			=> $this->get_categs(),
										'inputlabel'			=> __( 'Select category to exclude', 'catloop')
										)
									)	
							),
						'catloop_order_opt' => array(
							'type'		=> 'multi_option',
							'title'		=> __('Post order options', 'catloop'),
							'shortexp'		=> __('Set up the order of the posts.', 'catloop'),
							'selectvalues'	=> array(
								'catloop_orderby' => array(
									'type'			=> 'select',
									'selectvalues'	=> array(
											'ID' 		=> array('name' => __( 'Post ID', 'catloop' ) ),
											'title' 	=> array('name' => __( 'Title', 'catloop' ) ),
											'date' 		=> array('name' => __( 'Date', 'catloop' ) ),
											'modified' 	=> array('name' => __( 'Last Modified', 'catloop' ) ),
											'rand' 		=> array('name' => __( 'Random', 'catloop' ) ),							
											),
									'inputlabel'			=> __( 'Order posts by...', 'catloop')
									),
									'catloop_order' => array(
				 					 	'type'			=> 'select',
				 						'selectvalues'			=> array(
											'DESC' 		=> array('name' => __( 'Descending', 'catloop' ) ),
											'ASC' 		=> array('name' => __( 'Ascending', 'catloop' ) ),
											),
				 						'inputlabel'			=> __( 'Select sort order.', 'catloop')
										),	  			
									)	
							),
		
						'authors_config' => array(
							'type'		=> 'multi_option',
							'title'		=> __('CatLoop Author Setup', 'catloop'),
							'shortexp'  => __( 'Get the author ID from Users -> All users by hovering over the username.', 'catloop'),
							'selectvalues'		=> array(
								'authors' => array(
								'type'			=> 'text',
								'inputlabel'	=> __( 'Enter the numerical user ID of the authors.', 'catloop')
				 				)			
							)	
						),
						'pp_offset' => array(
							'type'		=> 'multi_option',
							'title'		=> __('Post Per Page & Offset', 'catloop'),
							'selectvalues'	=> array(
									'showposts' => array(
										'type'			=> 'text',					
										'inputlabel'			=> __( 'Type in the number of posts to show per page.', 'catloop')
										),
									'offset' => array(
										'type'			=> 'text',					
										'inputlabel'			=> __( 'Hide ... posts.', 'catloop')
				 						),		 		  				 			
									)		
								),

						'layout_opts' => array(
								'title' 	=> __( 'Layout & Config', 'catloop' ),
								'type'		=> 'multi_option',
								'selectvalues'		=> array(
										'blog_layout_mode' => array(
												'type'		=> 'select',
												'default'	=> 'magazine',
												'selectvalues'	=> array(
													'magazine'	=> array('name' => __( "Magazine Layout Mode", 'catloop' )),
													'blog'		=> array('name' => __( "List Layout Mode", 'catloop' ))
													),
												'inputlabel'		=> __( 'Posts Layout Mode', 'catloop' ),
											),
										'show_content' => array(
												'type'		=> 'check',
												'inputlabel'		=> 'Show full content of posts?',
												),
											)
										),
						'metabar_opts' => array(
								'title' 	=> __( 'Metabar Config', 'catloop' ),
								'type'		=> 'multi_option',
								'exp'			=> __( 'Use shortcodes to control the dynamic information in your metabar. Example shortcodes you can use are: <ul><li><strong>[post_categories]</strong> - List of categories</li><li><strong>[post_edit]</strong> - Link for admins to edit the post</li><li><strong>[post_tags]</strong> - List of post tags</li><li><strong>[post_comments]</strong> - Link to post comments</li><li><strong>[post_author_posts_link]</strong> - Author and link to archive</li><li><strong>[post_author_link]</strong> - Link to author URL</li><li><strong>[post_author]</strong> - Post author with no link</li><li><strong>[post_time]</strong> - Time of post</li><li><strong>[post_date]</strong> - Date of post</li><li><strong>[post_type]</strong> - Type of post</li></ul>', 'catloop' ),
								'selectvalues'		=> array(
										'metabar_standard' => array(
											'default'		=> 'By [post_author_posts_link] On [post_date] &middot; [post_comments] [post_edit]',
											'type'			=> 'text',
											'inputlabel'			=> __( 'Configure Full Width Post Metabar', 'catloop' ),
										),
										'metabar_clip' => array(
											'default'		=> 'On [post_date] By [post_author_posts_link] [post_edit]',
											'type'			=> 'text',
											'inputlabel'			=> __( 'Configure Clip Metabar', 'catloop' ),
											),
										)
								),
						'catloop_thumbs' => array(
								'title' 	=> __( 'Thumbnails', 'catloop' ),
								'type'		=> 'multi_option',
								'selectvalues'		=> array(	
										'excerpt_mode_full' => array(
										'type'		=> 'select',
										'default'	=> 'left',
										'selectvalues'	=> array(
												'left'			=> array( 'name' => __( 'Left Justified', 'catloop' ), 'offset' => '0px -50px' ),
												'top'			=> array( 'name' => __( 'On Top', 'catloop' ), 'offset' => '0px 0px', 'version' => 'pro' ),
												'left-excerpt'	=> array( 'name' => __( 'Left, In Excerpt', 'catloop' ), 'offset' => '0px -100px' ),
												'right-excerpt'	=> array( 'name' => __( 'Right, In Excerpt', 'catloop' ), 'offset' => '0px -150px', 'version' => 'pro' ),
												),
										'inputlabel'		=> __( 'Thumbs in the List Layout', 'catloop' ),
										),			
								'excerpt_mode_clip' => array(
										'type'		=> 'select',
										'default'	=> 'left',
										'selectvalues'	=> array(
												'left'			=> array( 'name' => __( 'Left Justified', 'catloop' ), 'offset' => '0px -50px' ),
												'top'			=> array( 'name' => __( 'On Top', 'catloop' ), 'offset' => '0px 0px' ),
												'left-excerpt'	=> array( 'name' => __( 'Left, In Excerpt', 'catloop' ), 'offset' => '0px -100px' ),
												'right-excerpt'	=> array( 'name' => __( 'Right, In Excerpt', 'catloop' ), 'offset' => '0px -150px' ),
												),
										'inputlabel'		=> __( 'Thumbs in the Magazine Layout', 'catloop' ),
										),
								'hide_thumb' => array(
										'type'		=> 'check',
										'inputlabel'		=> __( 'Hide thumbs?', 'catloop' ),
										),
									)
								),		
						'excerpts_config' => array(
								'title' 	=> __( 'Excerpts', 'catloop' ),
								'type'		=> 'multi_option',
								'exp' => 	__( 'Excerpts are set to 55 words by default, but you can customise the length here for every instance of CatLoop. <br />By default WordPress strips all HTML tags from excerpts. You can use this option to allow certain tags. Simply enter the allowed tags in this field. Separate them by comma.<br/>Examples of allowed tags: &lt;i&gt; for icons, &lt;br&gt; for line breaks, &lt;a&gt; for links.', 'catloop' ),	
								'selectvalues'		=> array(
								
										'hide_excerpt' => array(
												'type'		=> 'check',
												'inputlabel'		=> __( 'Hide excerpts?', 'catloop' ),
												),
										'excerpt_len' => array(
												'default' 	=> 55,
												'type' 		=> 'text',
												'inputlabel'		=> __( 'Number of words.', 'catloop' ),
												),
										'excerpt_tags' => array(
												'default' 	=> '<a>,<i>',
												'type' 		=> 'text',
												'inputlabel'		=> __( 'Allowed Tags', 'catloop' ),
												)
											)
										),
						'read_more_config' => array(
								'title' => __('The Read More Link', 'catloop'),
								'type'	=> 'multi_option',
								'exp' =>  __( 'This text will be used as the link to your full article when viewing articles on your posts page (when excerpts are turned on). It supports font icons if added as with html tags. For instance, adding a Fast Forward icon after the link would be done with: My Custom Read More &lt;i class="icon-fast-forward"&gt; &lt;/i&gt <br />To apply classes, simply add your class name, without dots, commas or anything else. If you want to make the link a big red button, just use btn btn-large btn-important.', 'catloop' ),
									'selectvalues'	=> array(
										'continue_reading_text' => array(
													'default'	=> __( 'Read More &raquo;', 'catloop' ),
													'type'		=> 'text',
													'inputlabel'		=> __( 'Continue Reading Link Text', 'catloop' ),
													),
										'continue_reading_classes' => array(
												'type'		=> 'text',
												'inputlabel'		=> __( 'Additional Classes for the Read More Link', 'catloop' ),
												
												),					
											)
								),
						'results_opts' => array(
								'title'		=> __('Results pagination', 'catloop'),
								'type'		=> 'color_multi',
								'selectvalues'		=> array(
										'pages_bg_color' => array(
											'type'			=> 'color',					
											'inputlabel'			=> __( 'Background color for pagination.', 'catloop')
				 							),
										'pages_link_color' => array(
											'type'			=> 'color',					
											'inputlabel'			=> __( 'Color for page link.', 'catloop')
				 							),
										'paged_bg_color' => array(
											'type'			=> 'color',					
											'inputlabel'			=> __( 'Background color for pagination current page and hover.', 'catloop')
				 							),
			 							'paged_link_color' => array(
											'type'			=> 'color',					
											'inputlabel'			=> __( 'Color for current page link and hover.', 'catloop')
				 							),
			 								

										)
						),
						'catloop_paginate' => array(
				 				'type'			=> 'select',
				 				'title'		=> __('Pagination Type', 'catloop'),
				 				'exp'			=> __( 'NOTE: If you are using the post offset to hide a number of posts, you should use the CatLoop Pagination.', 'catloop'),
				 							'selectvalues'			=> array(
													'cl_paged' 		=> array('name' => __( 'CatLoop Pagination', 'catloop' ) ),
													'pl_paged' 		=> array('name' => __( 'PageLines Pagination', 'catloop' ) ),
													'not_paged' 	=> array('name' => __( 'No Pagination', 'catloop' ) ),
													),
				 							'inputlabel'			=> __( 'Choose which pagination to use, if any.', 'catloop')
				 							)
			);
			
			
			$metatab_settings = array(
					'id' 		=> 'catloop_meta',
					'name' 		=> __( 'CatLoop', 'catloop' ),
					'icon' 		=> $this->icon,
					'clone_id'	=> $settings['clone_id'],
					'active'	=> $settings['active']
				);

			register_metatab( $metatab_settings, $page_metatab_array );
		}

	    
		
		

	   
function section_template($clone_id) {
			
		global $pagelines_layout;
		global $post;
		global $wp_query;	
		global $paged;
		
					
		if( get_query_var( 'paged' ) )
			$catloop_page = get_query_var( 'paged' );
		else {
		
			if( get_query_var( 'page' ) )
			
			$catloop_page = get_query_var( 'page' );
			
		else
		
			$catloop_page = 1;
			
			set_query_var( 'paged', $catloop_page );
			
			$paged = $catloop_page;
			
		}
		
		if (empty($paged)) {
		
			$paged = 1;
			
		}
		
		//Get Option Values
		
		$postsperpage = ploption( 'showposts', $this->oset ) ? ploption( 'showposts', $this->oset ) : '';
		
		$order = ploption ( 'catloop_order', $this->oset ) ? ploption ( 'catloop_order', $this->oset ) : '';
		
		$orderby = ploption ( 'catloop_orderby', $this->oset ) ? $orderby = ploption ( 'catloop_orderby', $this->oset ) : '';
		
		$category = ploption( 'categs', $this->oset ) ? ploption( 'categs', $this->oset ) : '';
		
		$exclude = ploption( 'catloop_exclude', $this->oset ) ? ploption( 'catloop_exclude', $this->oset ) : '';
		
		$paginate = ploption( 'catloop_paginate', $this->oset ) ? ploption( 'catloop_paginate', $this->oset ) : '';
		
		$offby = ploption( 'offset', $this->oset ) ? ploption( 'offset', $this->oset ) : '';
			
		$authors = ploption( 'authors', $this->oset ) ? ploption( 'authors', $this->oset ) : '';
		//Make conversions to avoid errors and warnings
		if (isset($category)) {
		
		   $idList = json_encode($category);
		   
		}
		
		if (isset($exclude)) {
		
			$idListexclude = json_encode($exclude);
			
		}
		
		//The post offset
		$page_offset = $offby + ( ($paged-1) * $postsperpage );	
		
		

		
		if ( $category && is_paged()) {	
		$args = array(
			'cat'        		=> $idList,
			'category__not_in'  	=> $idListexclude,
			'author'		=> $authors,
			'order'           	=> $order,
			'paged' 		=> $paged, 
			'page' 			=> $paged, 
			'orderby' 		=> $orderby,
			'offset'		=> $page_offset,
			'posts_per_page' 	=> $postsperpage
			
			);
		}
		else {
		$args = array(
			'cat'      		=> $idList,
			'category__not_in'  	=> $idListexclude,
			'author'		=> $authors,
			'order'           	=> $order,
			'paged' 		=> $paged, 
			'page' 			=> $paged, 
			'orderby' 		=> $orderby,
			'offset'		=> $offby,
			'posts_per_page' 	=> $postsperpage
			
			);
		}
			
			//The query

			$catloop = query_posts($args);

			if(have_posts()) {
				$this->catloop_section_heading();
			}
			else 
				return;
			//The Loop
			
			if( have_posts() )
			
			while ( have_posts() ) : the_post();  

			
			//Get the article with proper markup for 2 column layout
			
			$this->count = $wp_query->current_post;  // Used to get the number of the post as we loop through them.
			$this->clipcount = $wp_query->current_post;
			$this->post_count = $wp_query->post_count;  // Used to prevent markup issues when there aren't an even # of posts.
			$this->paged = intval(get_query_var('paged')); // Control output if on a paginated page
			$clip = ( $this->catloop_show_clip( $this->count, $this->paged ) ) ? true : false;
		
			$format = ( $clip ) ? 'clip' : 'feature';
			$clip_row_start = ( $this->clipcount % 2 == 0 ) ? true : false;
			$clip_right = ( ($this->clipcount+1 ) % 2 == 0 ) ? true : false;
		
			$clip_row_end = ( $clip_right || $this->count == $this->post_count ) ? true : false;

			$post_type_class = ( $clip ) ? ( $clip_right ? 'clip clip-right' : 'clip' ) : 'fpost';

			$catloop_post_classes = apply_filters( 'pagelines_get_article_post_classes', sprintf( '%s post-number-%s', $post_type_class, $this->count ) );

			$post_classes = join( ' ', get_post_class( $catloop_post_classes ) );

			$wrap_start = ( $clip && $clip_row_start ) ? sprintf( '<div class="clip_box fix">' ) : '';
			$wrap_end = ( $clip && $clip_row_end ) ? sprintf( '</div>' ) : '';

			$post_args = array(
					'header'		=> $this->catloop_post_header( $format ),
					'entry'			=> $this->catloop_post_entry(),
					'classes'		=> $post_classes,
					'pad-class'		=> ( $clip ) ? 'hentry-pad blocks' : 'hentry-pad',
					'wrap-start'	=> $wrap_start,
					'wrap-end'		=> $wrap_end,
					'format'		=> $format,
					'count'			=> $this->count
					);
		
			$post_args['markup-start'] = sprintf(
					'%s<article class="%s" id="post-%s"><div class="%s">',
					$post_args['wrap-start'],
					$post_args['classes'],
					$post->ID,
					$post_args['pad-class']
					);

			$post_args['markup-end'] = sprintf(
				'</div></article>%s',
				$post_args['wrap-end']
				);

			$original = join(array(
				$post_args['markup-start'],
				$post_args['header'],
				$post_args['entry'],
				$post_args['markup-end']
				));
	
		echo apply_filters( 'pagelines_get_article_output', $original, $post, $post_args );
		
		// Count posts
		if( $clip )
			$this->clipcount++;
		
		// Count posts
			$this->count++;		
		
		endwhile;//End while
			else
			return; //Prevent 404 if reaching the end of one query so that it doesn't look messed up
		
		if ($paginate == 'cl_paged'){
		
				$range = 1;
				$pages = '';
     			$showitems = ($range * 2)+1;
     	
	 			global $paged;
	 	
	 			$page_offset = $offby + ( ($paged-1) * $postsperpage );	
	 	
	 			if(empty($paged)){
	 				$paged = 1;
	 			}

	 			if($pages == ''){
         			global $wp_query;

         			if ($postsperpage) {
         				$maxpages = ($wp_query->found_posts-$offby)/$postsperpage;
		 				$pages = ceil ($maxpages);
         			}
         	
		 			if (!$postsperpage) {
         				$pages = $wp_query->max_num_pages;
         			}
		 	
		 			if(!$pages){  
		        		$pages = 1;
					}
		 		}
		
		 		if(1 != $pages){
         	
	        		echo "<div class=\"cloop-$clone_id pagination\"><span>Page ".$paged." of ".$pages."</span>";
         
        			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         
        			if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
         
        			for ($i=1; $i <= $pages; $i++){
         
             			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
             
                			echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
                 
             			}
             
         			}
         
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         
         echo "</div>\n";
		
        	$paged_bg_color = pl_hashify(ploption( 'paged_bg_color', $this->oset ));
  			$paged_link_color = pl_hashify(ploption( 'paged_link_color', $this->oset ));
  			$pages_bg_color = pl_hashify(ploption( 'pages_bg_color', $this->oset ));
  			$pages_link_color = pl_hashify(ploption( 'pages_link_color', $this->oset ));
            	?>
           		 <style type="text/css">
            				.cloop-<?php echo $clone_id ?> {
									clear:both;
									padding:20px 0;
									position:relative;
									font-size:13px;
									line-height:13px;
							}
              				.cloop-<?php echo $clone_id ?> span, .cloop-<?php echo $clone_id ?> a {
									color:<?php echo $pages_link_color;?>;
									background:<?php echo $pages_bg_color;?>;
									display:block;
									float:left;
									margin: 2px 2px 2px 0;
									padding:6px 9px 5px 9px;
									text-decoration:none;
									width:auto;
  									
							}
							.cloop-<?php echo $clone_id ?> a:hover{
									color:<?php echo $paged_link_color;?>;
									background: <?php echo $paged_bg_color;?>;
							}
							.cloop-<?php echo $clone_id ?> .current{
									padding:6px 9px 5px 9px;
									background: <?php echo $paged_link_color;?>;
									color:<?php echo $paged_bg_color;?>;		
							}		
							

           		 </style>  
            <?php 

     } 
		
		}
		if ($paginate == 'pl_paged') {
		
		pagelines_pagination();
		
		}
		if ($paginate == 'not_paged') {
		
		return;
		
		}
		
		wp_reset_query();	//put everything back to how it was
		
		}

	 
		
function get_categs() {
			
	   $cats = get_categories();
	   
	   foreach( $cats as $cat )
	   
	      $categories[ $cat->cat_ID ] = array( 'name' => $cat->name );
	      
	   return ( isset( $categories) ) ? $categories : array();
	   
	}
	
function catloop_excerpt($text){
	$allowed_tags = (ploption('excerpt_tags', $this->oset )) ? ploption('excerpt_tags',$this->oset ) : '';
	$excerpt_word_count = (ploption('excerpt_len',$this->oset )) ? ploption('excerpt_len', $this->oset ) : 55;
		
	$raw_excerpt = $text;
	if ( '' == $text ) {
    //Retrieve the post content. 
    $text = get_the_content('');
 
    //Delete all shortcode tags from the content. 
    $text = strip_shortcodes( $text );
 
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
     
    $text = strip_tags($text, $allowed_tags);
    $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 

    $excerpt_end = '...'; /*** MODIFY THIS. change the excerpt endind to something else.***/
    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 
     
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text = $text . $excerpt_more;
    } else {
        $text = implode(' ', $words);
    }
}
return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);

	}		

function catloop_section_heading(){
	$catloop_heading = ploption( 'catloop_heading', $this->oset ) ? ploption( 'catloop_heading', $this->oset ) : '';
		$catloop_subhead = ploption( 'catloop_subhead', $this->oset ) ? ploption( 'catloop_subhead', $this->oset ) : '';
		$catloop_alignment = ploption( 'head_alignment', $this->oset ) ? ploption( 'head_alignment', $this->oset ) : 'left';
		if ($catloop_heading && !$catloop_subhead) {
			?>
			<div class="catloop-header">
			<h2 class="catloop-head" data-sync="catloop_heading" style="text-align: <?php echo $catloop_alignment?>"><?php echo $catloop_heading ?></h2>
			</div>
			<?php
		}
		if ($catloop_heading && $catloop_subhead) {
			?>
			<div class="catloop-header">
			<h2 class="catloop-head" data-sync="catloop_heading" style="text-align: <?php echo $catloop_alignment?>"><?php echo $catloop_heading ?></h2>
			<div class="catloop-subhead" data-sync="catloop_subhead" style="text-align: <?php echo $catloop_alignment?>"><?php echo $catloop_subhead ?></div>
			</div>
			<?php
		}
		if (!$catloop_heading && !$catloop_subhead) {
			return; //do nothing
		}
}		

function catloop_post_entry(){

		$id = get_the_ID();

		if( $this->catloop_show_content( $id ) ){

			$excerpt_mode = (ploption( 'excerpt_mode_full', $this->oset  )) ? ploption( 'excerpt_mode_full', $this->oset  ) : 'top';


			if( ( $excerpt_mode == 'left-excerpt' || $excerpt_mode == 'right-excerpt' ) && is_single() && $this->catloop_show_thumb( $id ) )
				$thumb = $this->catloop_post_thumbnail_markup( $excerpt_mode );
			else
				$thumb = '';

			$post_entry = sprintf( '<div class="entry_wrap fix"><div class="entry_content">%s%s</div></div>', $thumb, $this->catloop_post_content() );

			return apply_filters( 'pagelines_post_entry', $post_entry );

		} else
			return '';
	}


function catloop_post_content(){

		$cr_link = ploption('continue_reading_text', $this->oset );
		
		$this->continue_reading = ($cr_link) ? $cr_link : __('Read More &raquo;', 'catloop');
		
		ob_start();

			pagelines_register_hook( 'pagelines_loop_before_post_content', 'theloop' ); // Hook

			$content = get_the_content( $this->continue_reading );

			$content .= pledit( get_the_ID() );

			echo apply_filters( 'the_content', $content );


			pagelines_register_hook( 'pagelines_loop_after_post_content', 'theloop' ); // Hook

		$the_content = ob_get_clean();

		return $the_content;

	}


function catloop_post_header( $format = '' ){

			global $post;

			$id = get_the_ID();

			$excerpt_mode = ( $format == 'clip' ) ? ploption( 'excerpt_mode_clip', $this->oset ) : ploption( 'excerpt_mode_full', $this->oset  );
			
			$excerpt_mode = ( $excerpt_mode ) ? $excerpt_mode : 'top';
			
			$thumb = ( $this->catloop_show_thumb( $id ) ) ? $this->catloop_post_thumbnail_markup( $excerpt_mode, $format ) : '';

			$excerpt_thumb = ( $thumb && ( $excerpt_mode == 'left-excerpt' || $excerpt_mode == 'right-excerpt' ) ) ? '' : $thumb;

			$excerpt = ( $this->catloop_show_excerpt( $id ) ) ? $this->catloop_post_excerpt_markup( $excerpt_mode, $excerpt_thumb ) : '';
			
			$classes = 'post-meta fix ';
			$classes .= ( ! $this->catloop_show_thumb( $id ) ) ? 'post-nothumb ' : '';
			$classes .= ( ! $this->catloop_show_content( $id ) ) ? 'post-nocontent ' : '';

			$title = sprintf( '<section class="bd post-title-section fix"><div class="post-title fix">%s</div>%s</section>', $this->catloop_get_post_title( $format ), $this->catloop_get_post_metabar( $format ) );

			if( ( $excerpt_mode == 'left-excerpt' || $excerpt_mode == 'right-excerpt' ) && ! is_single() )
				$post_header = sprintf( '<section class="%s"><section class="bd post-header fix" >%s %s%s</section></section>', $classes, $title, $thumb, $excerpt );
			elseif( $excerpt_mode == 'top' )
				$post_header = sprintf( '<section class="%s">%s<section class="bd post-header fix" >%s %s</section></section>',$classes, $thumb, $title, $excerpt );
			elseif( $excerpt_mode == 'left' )
				$post_header = sprintf( '<section class="%s media">%s<section class="bd post-header fix" >%s %s</section></section>', $classes, $thumb, $title, $excerpt );
			else
				$post_header = sprintf( '<section class="%s">%s<section class="bd post-header fix" >%s %s</section></section>',$classes, '', $title, $excerpt );

			return apply_filters( 'pagelines_post_header', $post_header, $format );

	}

function catloop_post_excerpt_markup( $mode = '', $thumbnail = '' , $text = '') {
		
		ob_start();

		pagelines_register_hook( 'pagelines_loop_before_excerpt', 'theloop' ); // Hook

		if($mode == 'left-excerpt' || $mode == 'right-excerpt')
			printf( '<aside class="post-excerpt">%s %s</aside>', $thumbnail, $this->catloop_excerpt($text) );
		else
			printf( '<aside class="post-excerpt">%s</aside>', $this->catloop_excerpt($text) );


		if(pagelines_is_posts_page() && !$this->catloop_show_content( get_the_ID() )) 
			echo $this->catloop_get_continue_reading_link( get_the_ID() );

		pagelines_register_hook( 'pagelines_loop_after_excerpt', 'theloop' ); // Hook

		$catloop_excerpt = ob_get_clean();

		return apply_filters('pagelines_excerpt', $catloop_excerpt);

	}

function catloop_post_thumbnail_markup( $mode = '', $format = '', $frame = '' ) {

		$thumb_width = get_option( 'thumbnail_size_w' );

		$classes = 'post-thumb img fix';

		$percent_width  = ( $mode == 'top' ) ? 100 : 25;

        	$style = ( 'top' == $mode ) ? 'width: 100%' : sprintf( 'width: %s%%; max-width: %spx', apply_filters( 'pagelines_thumb_width', $percent_width ), $thumb_width );

		if ( $mode == 'left-excerpt' )
			$classes .= ' alignleft';
		elseif ( $mode == 'right-excerpt' )
			$classes .= ' alignright';

		global $post;

		$img = ( $mode == 'top' ) ? get_the_post_thumbnail( null, 'landscape-thumb' ) : get_the_post_thumbnail( null, 'thumbnail' );

		$the_image = sprintf( '<span class="c_img">%s</span>', $img );

		$thumb_link = sprintf( '<a class="%s" href="%s" rel="bookmark" title="%s %s" style="%s">%s</a>', $classes, get_permalink( $post ), __( 'Link To', 'catloop' ), the_title_attribute( array( 'echo' => false ) ), $style, $the_image );

        $output = ( 'top' == $mode ) ? sprintf( '<div class="full_img fix">%s</div>', $thumb_link ) : $thumb_link;

		return apply_filters( 'pagelines_thumb_markup', $output, $mode, $format );

	}

function catloop_get_post_metabar( $format = '' ) {

		$metabar = '';
		$before = '<em>';
		$after = '</em>';

		if( $format == 'clip'){

			$metabar = ( ploption( 'metabar_clip', $this->oset ) ) ? $before . ploption( 'metabar_clip', $this->oset  ) . $after : sprintf( '%s%s [post_date] %s [post_author_posts_link] [post_edit]%s', $before, __('On','catloop'), __('By','catloop'), $after );

		} else {

			$metabar = ( ploption( 'metabar_standard', $this->oset  ) ) ? $before . ploption( 'metabar_standard', $this->oset  ) . $after : sprintf( '%s%s [post_author_posts_link] %s [post_date] &middot; [post_comments] &middot; %s [post_categories] [post_edit]%s', $before, __('By','catloop'), __('On','catloop'), __('In','catloop'), $after);

		}
		add_filter('pagelines_post_metabar', 'do_shortcode', 20);
		if( $format == 'clip'){
		return sprintf( '<div class="metabar"><div class="metabar-pad" data-sync="metabar_clip">%s</div></div>', apply_filters('pagelines_post_metabar', $metabar, $format) );
		}
		elseif( $format !== 'clip') {
		return sprintf( '<div class="metabar"><div class="metabar-pad" data-sync="metabar_standard">%s</div></div>', apply_filters('pagelines_post_metabar', $metabar, $format) );
		}
	}


function catloop_get_post_title( $format = '' ){

		global $post;
		global $pagelines_ID;

			if ( is_singular() )
				$title = sprintf( '<h1 class="entry-title">%s</h1>', apply_filters( 'pagelines_post_title_text', get_the_title() ) );
			elseif( $format == 'clip')
				$title = sprintf( '<h4 class="entry-title"><a href="%s" title="%s" rel="bookmark">%s</a></h4>', get_permalink( $post ), the_title_attribute('echo=0'), apply_filters( 'pagelines_post_title_text', get_the_title() ) );
			else
				$title = sprintf( '<h2 class="entry-title"><a href="%s" title="%s" rel="bookmark">%s</a></h2>', get_permalink( $post ), the_title_attribute('echo=0'), apply_filters( 'pagelines_post_title_text', get_the_title() ) );

		
		return apply_filters('pagelines_post_title_output', $title) . "\n";

	}


function catloop_get_continue_reading_link($post_id){
		$cr_link = ploption('continue_reading_text', $this->oset );
		$cr_class = ploption('continue_reading_classes', $this->oset ); //Get custom read more classes
		$this->continue_reading = ($cr_link) ? $cr_link : __('Read More &raquo;', 'catloop');
		$link = sprintf(
			'<a class="continue_reading_link %s" data-sync="continue_reading_text" href="%s" title="%s %s">%s</a>',
			$cr_class, //apply classes to link
			get_permalink(),
			__("View", 'catloop'),
			the_title_attribute(array('echo'=> 0)),
			$this->continue_reading
		);

		return apply_filters('continue_reading_link', $link);
	}

function catloop_show_thumb($post = null, $location = null){
		global $post;
		$thumb = has_post_thumbnail($post->ID);
		if(ploption('hide_thumb', $this->oset ) || (! $thumb))
			return false;
		else
			return true;
				
	}

function catloop_show_excerpt( $post = null ){

	if( ploption('hide_excerpt', $this->oset ))
		return false;
	else
		return true;

			
	}

function catloop_show_content($post = null){
	// For Hook Parsing
	if( is_admin() )
		return true;

	elseif(ploption('show_content', $this->oset ))
		return true;

	else
		return false;

	}

	
function catloop_show_clip($count, $paged){

		if(ploption('blog_layout_mode', $this->oset ) != 'magazine')
			return false;
		else
			return true;
	

	}
	}//End Class CatLoopDMS