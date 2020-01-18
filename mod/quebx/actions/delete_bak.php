<?php

// Make sure we're logged in (send us to the front page if not)
if (!elgg_is_logged_in()) forward();

// Get input data
$guid = (int) get_input('guid');
		
// Make sure we actually have permission to edit
$quebx = get_entity($guid);
//if (($quebx->getSubtype() == "quebx" || $quebx->getSubtype() == "component" ) && $quebx->canEdit()) {
	
	// Get owning user
	$owner = get_entity($quebx->getOwner());
				
	// Delete the images

	$prefix = "quebx/".$guid;
		
	$tiny = $prefix."tiny.jpg";
	$small = $prefix."small.jpg";
	$medium = $prefix."medium.jpg";
	$large = $prefix."large.jpg";
	$master = $prefix.".jpg";
				
	if ($tiny) {
		$delfile = new ElggFile();
		$delfile->owner_guid = $owner->guid;
		$delfile->setFilename($tiny);
		$delfile->delete();
	}

	if ($small) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($small);
					$delfile->delete();
				}

				if ($medium) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($medium);
					$delfile->delete();
				}

				if ($large) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($large);
					$delfile->delete();
				}

				if ($master) {

					$delfile = new ElggFile();
					$delfile->owner_guid = $owner->guid;
					$delfile->setFilename($master);
					$delfile->delete();
				}


		// Delete the item
				$rowsaffected = $quebx->delete();
				if ($rowsaffected > 0) {
		
		// Success message
					system_message(elgg_echo("quebx:deleted"));
				} else {
					register_error(elgg_echo("quebx:notdeleted"));
				}
				
			// Forward to the referring page
//			forward(elgg_get_site_url() . "quebx");
            forward(REFERRER);
//		}
		
?>
