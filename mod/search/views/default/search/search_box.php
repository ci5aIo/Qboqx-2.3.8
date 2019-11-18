<?php
/**
 * Search box
 *
 * @uses $vars['value'] Current search query
 * @uses $vars['class'] Additional class
 */
$context = elgg_get_context();

Switch ($context){
    case 'dashboard':
        $search_form = "<form action='#' onsubmit='tracker.preventDefault.apply(this,arguments)' class='search_bar'>
                          <a class='magnify'></a>
                          <input name='search' type='text' aria-label='Search space' placeholder='Search space' autocomplete='off' autocorrect='off' autocapitalize='off' spellcheck='false' value=''>
                          <button class='search_help anchor' aria-label='search help' }='' type='button'>?</button>
                        </form>";
        break;
    default:
        $value = "";
        if (array_key_exists('value', $vars)) {
        	$value = $vars['value'];
        } elseif ($value = get_input('q', get_input('tag', NULL))) {
        	$value = $value;
        }
        
        $class = "elgg-search";
        if (isset($vars['class'])) {
        	$class = "$class {$vars['class']}";
        }
        
        // @todo - create function for sanitization of strings for display in 1.8
        // encode <,>,&, quotes and characters above 127
        if (function_exists('mb_convert_encoding')) {
        	$display_query = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
        } else {
        	// if no mbstring extension, we just strip characters
        	$display_query = preg_replace("/[^\x01-\x7F]/", "", $value);
        }
        
        // render placeholder separately so it will double-encode if needed
        $placeholder = htmlspecialchars(elgg_echo('search'), ENT_QUOTES, 'UTF-8');
        
        $search_attrs = elgg_format_attributes(array(
        	'type' => 'text',
        	'class' => 'search-input',
        	'size' => '21',
        	'name' => 'q',
        	'autocapitalize' => 'off',
        	'autocorrect' => 'off',
        	'required' => true,
        	'value' => $display_query,
        ));
        $action = elgg_get_site_url()."search";
        $search_form = "<form class='$class' action= $action method='get'>
        	<fieldset>
        		<input placeholder='$placeholder' $search_attrs>
        		<input type='hidden' name='search_type' value='all'>
        		<input type='submit' value='".elgg_echo('search:go')."' class='search-submit-button'>
        	</fieldset>
        </form>";
}
echo $search_form;
        ?>
<!--    <form class="<?php echo $class; ?>" action="<?php echo elgg_get_site_url(); ?>search" method="get">
        	<fieldset>
        		<input placeholder="<?php echo $placeholder; ?>" <?php echo $search_attrs; ?> />
        		<input type="hidden" name="search_type" value="all" />
        		<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search-submit-button" />
        	</fieldset>
        </form>
 -->        
     