<?php
/**
 * Elgg header logo
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
$site_description = $site->description;
$site_url = elgg_get_site_url()."dashboard/";
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
