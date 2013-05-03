<?php
/*
=======
Section: CatLoop
Author: Anca Enache
Author URI: http://anthalis.me/
Version: 1.3.4
Description: An easy to use drag & drop category loop
Long: Pull it in the content area of a page template to convert it into a custom category page! Supports pagination, custom no. of post per page. Do not use with the Blog template or any other dynamic templates that appear under Pagelines Page Options.  
Class Name: CatLoop
Workswith: main, templates
Cloning: true
Demo: http://catloop.anthalis.info/quick-how-to-guide-demo/
External: http://catloop.anthalis.info/documentation/
*/	
class CatLoop extends PageLinesSection {

	function section_template() {
	
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
		$postsperpage = ploption( 'showposts', $this->oset ) ? ploption( 'showposts', $this->oset ) : $postsperpage;
		$order = ploption ( 'catloop_order', $this->oset ) ? ploption ( 'catloop_order', $this->oset ) : '';
		$orderby = ploption ( 'catloop_orderby', $this->oset ) ? $orderby = ploption ( 'catloop_orderby', $this->oset ) : '';
		$source = ploption ( 'catloop_source', $this->oset ) ? ploption ( 'catloop_source', $this->oset ) : '';
		$category = ploption( 'categs', $this->oset ) ? ploption( 'categs', $this->oset ) : '';
		$exclude = ploption( 'catloop_exclude', $this->oset ) ? ploption( 'catloop_exclude', $this->oset ) : '';
		$paginate = ploption( 'catloop_paginate', $this->oset ) ? ploption( 'catloop_paginate', $this->oset ) : '';
		$limitcats = ploption( 'catloop_all', $this->oset ) ? ploption( 'catloop_all', $this->oset ) : '';

	if( !$category ){
				echo setup_section_notify( $this, 'Set up the CatLoop to activate' );
				return;
			}
	if (empty($paged)) {
			$paged = 1;
	}
	if ( $category ) {
		$catloop= query_posts(array('cat' => $category, 'category__not_in'=> $exclude, 'posts_per_page' => $postsperpage, 'paged' => $paged, 'page' => $paged, 'orderby' => $orderby, 'order' => $order));
		}
	if ( $category & $limitcats ) {
		$catloop= query_posts(array('category__in' => $category, 'category__not_in'=> $exclude, 'posts_per_page' => $postsperpage, 'paged' => $paged, 'page' => $paged, 'orderby' => $orderby, 'order' => $order));
		}
		$catloop = new PageLinesPosts();
		$catloop->load_loop();
		if ($paginate){
		$this->anthalis_pagination();
		}
		else {
		pagelines_pagination();
		}
		wp_reset_query();
		}		
		
function anthalis_pagination($pages = '', $range = 1) {
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }
     if(1 != $pages)
          {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}
	function section_optionator( $settings ){
			$page_metatab_array = array(
						'catloop_regposts_opt' => array(
						'type'		=> 'multi_option',
						'title'		=> __('CatLoop Setup', 'catloop'),
						'shortexp'	=> __('Select the category you want to display in your CatLoop, the excluded category and the order of posts.', 'catloop'),
						'selectvalues'	=> array(
															'categs'		=> array(
																	'default'		=> '',
																	'type'			=> 'select',
																	'selectvalues'	=> $this->get_categs(),
																	'inputlabel'	=> __( 'Select category to include', 'catloop' ),
																),
															'catloop_exclude' => array(
																	'default'		=> '',
																	'type'			=> 'select',
																	'selectvalues'	=> $this->get_categs(),
																	'inputlabel'	=> __( 'Select category to exclude.', 'catloop' ),
																),
																
															'catloop_all' => array (
																	'default'=>'',
																	'type'=> 'check',
																	'inputlabel'=> __( 'Show only posts added exclusively to parent categories. Explanation: By default, a category listing in WordPress will show all posts filed under a main category, including posts in child and grandchild category that have not been added specifically in the main category. Provided you have posts in a parent category called Fruit and you do not want to show posts from the child category Apples, enable this option. NOTE: It will show posts from the child category if they are added to both the parent and the child category.','catloop' ),
																	),
																	),
																	
															),
															
																
						'catloop_order_opt' => array(
						'type'		=> 'multi_option',
						'title'		=> __('Post order options', 'catloop'),
						'shortexp'	=> __('Set up the order of the posts.', 'catloop'),
						'selectvalues'	=> array(
															'catloop_orderby' => array(
																	'default' => '',
																	'type' => 'select',
																	'selectvalues' => array(
																			'ID' 			=> array('name' => __( 'Post ID', 'catloop' ) ),
																			'title' 		=> array('name' => __( 'Title', 'catloop' ) ),
																			'date' 			=> array('name' => __( 'Date', 'catloop' ) ),
																			'modified' 		=> array('name' => __( 'Last Modified', 'catloop' ) ),
																			'rand' 			=> array('name' => __( 'Random', 'catloop' ) ),							
																			),
																	'inputlabel'	=> __( 'Order posts by...', 'pagelines' ),
																),

															'catloop_order' => array(
																	'default'	=> '',
																	'type'		=> 'select',
																	'selectvalues'	=> array(
																			'DESC' 		=> array('name' => __( 'Descending', 'catloop' ) ),
																			'ASC' 		=> array('name' => __( 'Ascending', 'catloop' ) ),
																			),
																	'inputlabel'	=> __( 'Select sort order.', 'pagelines' ),
																	),
						),
),
						'catloop_pag_opt' => array(
						'type'		=> 'multi_option', 
						'title'		=> __('Results Pagination Setup', 'catloop'), 
						'shortexp'	=> __('Set up the number of posts per page. You can use the optional pagination or the PL pagination section. ', 'catloop'),
						'selectvalues'	=> array(	
																	
															'showposts' => array (
																	'default' 		=>'',
																	'type'			=> 'text',
																	'inputlabel'	=> __( 'Type in the number of posts to show per page.', 'catloop' ),
																	),
															
															'catloop_paginate' => array (
																	'default'=>false,
																	'type'=> 'check',
																	'inputlabel'=> __( 'If you want to paginate your category posts, enable this option.','catloop'
																	),
																	),															
),
		),
			);
			$settings = wp_parse_args( $settings, $this->optionator_default );
			
			$metatab_settings = array(
					'id' 		=> 'catloop_meta',
					'name' 		=> __( 'CatLoop', 'catloop' ),
					'icon' 		=> $this->icon, 
					'clone_id'	=> $settings['clone_id'], 
					'active'	=> $settings['active']
				);

			register_metatab( $metatab_settings, $page_metatab_array );
		}

	function get_categs() {
	
		$cats = get_categories();
		foreach( $cats as $cat )
			$categories[ $cat->cat_ID ] = array( 'name' => $cat->name );
			
		return ( isset( $categories) ) ? $categories : array();
	}
	}