<?php
 /**
  * This template is used to print a single field in a view. It is not
  * actually used in default Views, as this is registered as a theme
  * function which has better performance. For single overrides, the
  * template is perfectly okay.
  *
  * Variables available:
  * - $view: The view object
  * - $field: The field handler object that can process the input
  * - $row: The raw SQL result that can be used
  * - $output: The processed output that will normally be used.
  *
  * When fetching output from the $row, this construct should be used:
  * $data = $row->{$field->field_alias}
  *
  * The above will guarantee that you'll always get the correct data,
  * regardless of any changes in the aliasing that might happen if
  * the view is modified.
  */
?>
<?php 

  $artist = 'Artist Unspecified';
  if (isset($row->field_field_artist['0']))
    $artist = $row->field_field_artist['0']['rendered']['#markup'];
  
  if (isset($row->field_field_art_image['0']))
    $artTitle = $row->field_field_art_image['0']['rendered']['#node']->title;
  else
    $artTitle = "Title Unspecified";
    
  $pre = strstr($output, 'title=',true);
  $post = strstr($output, 'class=', false);
  $newoutput = $pre.' title="'.$artist.' - '.$artTitle.'" '.$post;  
    
  print $newoutput; 
  
 // find the title=" substring and replace with $artist and $artTitle  
 $blah = '<a href="http://127.0.0.1:10088/sites/www.mccauleygallery.com/files/art-images/The%20Heights.jpg" title="The Heights" class="colorbox" rel="gallery-all"><img typeof="foaf:Image" src="http://127.0.0.1:10088/sites/www.mccauleygallery.com/files/styles/show_artwork/public/art-images/The%20Heights.jpg" width="350" height="250" alt="Tom Hoffman - The Heights Watercolor" title="Tom Hoffman - The Heights Watercolor" /></a>';
 
?>

