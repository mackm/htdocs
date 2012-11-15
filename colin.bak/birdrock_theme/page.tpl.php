<?php

/**
 * @file
 * Implemntation of the www.birdrock.com main content theme
 * 
 * History:
 *  	03/15/12 - Mack - Created
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>

  <div id="page-wrapper">
    <div id="page">

      <div id="header"><div class="section clearfix">
        <?php print render($page['header']); ?>
      </div></div> <!-- /#header /.section-->

      <!-- Layout is currently set up for three vertical columns; sidebar_first, main_wrapper, sidebar_second --> 

      
     
      <div class="banner" id="bannerLeft">
      <?php print render($page['banner_left']); ?>
      </div>
      
      <div class="banner" id="bannerCenter">
      <?php print render($page['banner_center']); ?>
      </div>

      <div class="banner" id="bannerRight">
      <?php print render($page['banner_right']); ?>
      </div>

     <!--       
      <div class="gutter"  id="rates">Rates</div>

      <div class="gutter"  id="advisorQuotes">quotes</div>
      -->
    
      <!-- /.section, /#sidebar-first --> 
	  <div id="photoBox">

      <?php if ($page['sidebar_first']): ?>
      <div class="sidebar-first" id="sidebar-first">
      <?php print render($page['sidebar_first']); ?>
      </div> 

	  <div id="sidebar-first-opacity"></div>
      <?php endif; ?>
      
   	  <?php if ($page['photos']): ?>
	  <?php print render($page['photos']); ?>
	  <?php endif; ?>
      </div>
   	  <!-- /.photos -->

 
      <div class="gutter"  id="gutterLeft">
      <?php print render($page['gutter_left']); ?>
	  </div>

	   <div id="content">
	
      <?php if ($page['tabs']): ?>
      <div id="menu-block-2_section" class='tabsBlock'>Section</div>
      <div id="menu-block-2_dots"><img src="http://www.swiftsurepreview3.com/colin/menu-block-2_dots.png" width=50></div>
      <div id="menu-block-2_tabs" class='tabsBlock'><?php print render($page['tabs']); ?></div><!-- /.tabs -->
	  <?php endif; ?>
  
	  <div id="mainContent">
      <?php print render($page['content']); ?>
       </div>
       </div> <!-- /#content -->
          
      <div class="gutter"  id="gutterRight">
      <?php print render($page['gutter_right']); ?>
	  </div>
       
      <?php if ($page['sidebar_second']): ?>
        <div id="sidebar-second" class="column sidebar">
          <?php print render($page['sidebar_second']); ?>
        </div><!-- /#sidebar-second -->
      <?php endif; ?>

      <div id="footer"><div class="section clearfix">
        <?php print render($page['footer']); ?>
      </div><!-- /#footer -->
     
    </div><!-- /#page -->
  </div><!-- /#page-wrapper -->
