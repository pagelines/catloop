
<?php
/*
=======
Section: CatLoop
Author: Anca Enache
Author URI: http://www.anthalis.dk
Version: 1.0
Description: An easy to use drag & drop category loop
Long: Pull it in the content area of a page template to convert it into a custom category page! Supports pagination, custom no. of post per page. Do not use with the Blog template or any other dynamic templates that appear under Pagelines Page Options.  
Class Name: CatLoop
Workswith: main 
Cloning: false
Demo: http://www.anthalis.dk/pagelines/
*/	
class CatLoop extends PageLinesSection {

	function section_template() {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : $paged; 
		$postsperpage = ploption( 'showposts', $this->oset ) ? ploption( 'showposts', $this->oset ) : $postsperpage;
		$order = ploption ( 'catloop_order', $this->oset ) ? ploption ( 'catloop_order', $this->oset ) : '';
		$orderby = ploption ( 'catloop_orderby', $this->oset ) ? $orderby = ploption ( 'catloop_orderby', $this->oset ) : '';
		$source = ploption ( 'catloop_source', $this->oset ) ? ploption ( 'catloop_source', $this->oset ) : '';
		$category = ploption( 'categs', $this->oset ) ? ploption( 'categs', $this->oset ) : '';	
		$exclude = ploption( 'catloop_exclude', $this->oset ) ? ploption( 'catloop_exclude', $this->oset ) : '';	
		$paginate = ploption( 'catloop_paginate', $this->oset ) ? ploption( 'catloop_paginate', $this->oset ) : '';

	if( !$category ){
				echo setup_section_notify( $this, 'Set up the CatLoop to activate' );
				return;
			}  		
	if (empty($paged)) {
			$paged = 1;
	}		
		$catloop = new WP_Query();	
		$catloop= query_posts(array('cat' => $category,'category__not_in'=> $exclude, 'posts_per_page' => $postsperpage, 'post_type' => 'post', 'paged' => $paged, 'orderby' => $orderby, 'order' => $order));
		$catloop = new PageLinesPosts();
		$catloop->load_loop();
		if ($paginate){
		$this->anthalis_pagination();
		}
		}

 

function anthalis_pagination($pages = '', $range = 1)
{  
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
						'title'		=> __('CatLoop Setup', 'pagelines'), 
						'shortexp'	=> __('Select the category you want to display in your CatLoop, the excluded category and the order of posts.', 'pagelines'),
						'selectvalues'	=> array(			
															'categs'		=> array(
																	'default'		=> '',
																	'version'		=> 'pro',
																	'type'			=> 'select',
																	'selectvalues'	=> $this->get_categs(),
																	'inputlabel'	=> __( 'Select Post Category', 'pagelines' ),
																),
															'catloop_exclude' => array(
																	'default'		=> '',
																	'version'		=> 'pro',
																	'type'			=> 'select',
																	'selectvalues'	=> $this->get_categs(),
																	'inputlabel'	=> __( 'Select Post Category to exclude. Best used if you have a Featured category, which you want to display in a slider on the same page template. By excluding that category with this option you avoid post duplication.', 'pagelines' ),
																),	
																
															'catloop_orderby' => array(
																	'default' => '',
																	'version'	=> 'pro',
																	'type' => 'select',
																	'selectvalues' => array(
																			'ID' 			=> array('name' => __( 'Post ID', 'pagelines' ) ),
																			'title' 		=> array('name' => __( 'Title', 'pagelines' ) ),
																			'date' 			=> array('name' => __( 'Date', 'pagelines' ) ),
																			'modified' 		=> array('name' => __( 'Last Modified', 'pagelines' ) ),
																			'rand' 			=> array('name' => __( 'Random', 'pagelines' ) ),							
																			),
																	'inputlabel'	=> __( 'Order posts by...', 'pagelines' ),
																),

															'catloop_order' => array(
																	'default'	=> '',
																	'version'	=> 'pro',
																	'type'		=> 'select',
																	'selectvalues'	=> array(
																			'DESC' 		=> array('name' => __( 'Descending', 'pagelines' ) ),
																			'ASC' 		=> array('name' => __( 'Ascending', 'pagelines' ) ),
																			),
																	'inputlabel'	=> __( 'Select sort order.', 'pagelines' ),
																	),	
															),
															),
															
						'catloop_pag_opt' => array(
						'type'		=> 'multi_option', 
						'title'		=> __('Results Pagination Setup', 'pagelines'), 
						'shortexp'	=> __('Set up the number of posts per page. You can use the optional pagination or the PL pagination section. ', 'pagelines'),
						'selectvalues'	=> array(	
																	
															'showposts' => array (
																	'default' 		=>'',
																	'version'		=> 'pro',
																	'type'			=> 'text',
																	'inputlabel'	=> __( 'Type in the number of posts to show per page.', 'pagelines' ),
																	),
																			
																	
															'catloop_paginate' => array (
																	'default'=>false,
																	'version' => 'pro',
																	'type'=> 'check',
																	'inputlabel'=> __( 'Check this to apply the CatLoop pagination to the section (Optional) or you can also use the PageLines Post/Page Pagination section.','pagelines'
																	),
																	),


															
												
),
		),		
			
			);
												
			$settings = wp_parse_args( $settings, $this->optionator_default );
			
			$metatab_settings = array(
					'id' 		=> 'catloop_meta',
					'name' 		=> __( 'CatLoop', 'pagelines' ),
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