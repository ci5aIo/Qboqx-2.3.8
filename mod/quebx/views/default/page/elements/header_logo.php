<?php
/**
 * Elgg header logo
 */
$site = elgg_get_site_entity();
$site_name = $site->name;
$site_description = $site->description;
$site_url = elgg_get_site_url()."dashboard/";
$header =
"<h1>
    <a class='elgg-heading-site' href='$site_url'>
        $site_name
    </a>
</h1>";
$tagline = 
"<h2>
	<span class='quebx-tagline-site'>$site_description
	</span>
</h2>";
$logo = elgg_view_icon('favicon-64.png');
$body = $header.'<br><br>'.$tagline;
$image = $logo;
echo elgg_view_image_block($image, $body, $vars);
/*


?>

<h1>
	<a class="elgg-heading-site" href="<?php echo $site_url; ?>">
		<?php echo $site_name; ?>
	</a>
</h1>
<br><br>
<h2>
	<span class="quebx-tagline-site"><?php echo $site_description; ?>
	</span>
</h2>
 */