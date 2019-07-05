<?php
$entity = elgg_extract('entity', $vars, false);
$parent_cid = elgg_extract('cid', $vars);
$cid    = quebx_new_id('c');
$guid   = $entity->getGUID();
$title  = $entity->title;

$form = "<div class='model'>
	<div>
		<div id='view8059' data-scrollable='true' class='edit details'>
			<section class='edit' data-aid='StoryDetailsEdit' aria-expanded='true' tabindex='-1'>
			  <section class='model_details'>
				<form action='#' onsubmit='tracker.preventDefault.apply(this,arguments)' class='story model'>
				  <section class='story_or_epic_header'>
					<div class='autosaves collapser story_collapser_$parent_cid' tabindex='0' aria-expanded='true' aria-label='Add something_xxx'></div>
					<fieldset class='name'><div class='AutosizeTextarea___2iWScFt6'><div class='AutosizeTextarea__container___31scfkZp'><textarea aria-label='story title' data-aid='name' data-focus-id='NameEdit--$cid' class='AutosizeTextarea__textarea___1LL2IPEy NameEdit___2W_xAa_R' name='story[name]'>$entity->title</textarea></div><div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt NameEdit___2W_xAa_R'><span>Add something_xxx</span><span>w</span></div></div></fieldset>
				  <a href='/story/show/147996385' type='button' class='autosaves maximize hoverable' id='story_maximize_$parent_cid' tabindex='-1' title='Switch to a full page view of this item'></a>
				  </section>
				  <aside>
					<div class='wrapper'>
					  <nav class='edit'><section class='controls'>
						  <div class='persistence use_click_to_copy'>
							
							<button class='autosaves button std close' type='submit' id='story_close_$cid' tabindex='-1'>Close</button>
						  </div>
						  <div class='actions'>
							
							  <div class='bubble'></div>

							  <button type='button' title='Copy this story's link to your clipboard' id='story_copy_link_$cid' data-clipboard-text='https://www.pivotaltracker.com/story/show/147996385' class='autosaves clipboard_button hoverable link left_endcap' tabindex='-1'></button>
							

							<div class='button_with_field'>
							  <button type='button' title='Copy this story's ID to your clipboard' id='story_copy_id_$cid' data-clipboard-text='#147996385' class='autosaves clipboard_button hoverable id use_click_to_copy' tabindex='-1'></button>
							  <input type='text' readonly='' class='autosaves id text_value' id='story_copy_id_value_$cid' value='#147996385' aria-label='story id'>
							</div>

							
						<button type='button' title='Clone this story' class='autosaves clone_story hoverable left_endcap' id='story_clone_button_$cid' tabindex='-1'></button>
						<button type='button' title='View the history of this story' class='autosaves history hoverable capped' id='story_history_button_$cid' tabindex='-1'></button>
						<button type='button' title='Delete this story' class='autosaves delete hoverable right_endcap' id='story_delete_button_$cid' tabindex='-1'></button>



							
						  </div>
						</section>

						</nav>


					  <div class='info_box_wrapper'>
						<div class='story state_box'>
						  <div class='state row'><div class='StoryState___2vkCAl9L' data-aid='StoryState'><em>State</em><div class='Dropdown StoryState__dropdown___3GU-2fu0 StoryState__dropdown--disabled___179oZpFv'><div class='Dropdown__content' data-aid='StoryState__dropdown'><button class='SMkCk__Button _3INnV__Button--default Dropdown__button StoryState__dropdownButton___LdR9Y07L undefined _3Xvsn__Button--disabled' disabled='' tabindex='0' type='button'><span class='StoryState__dropdown--label___3qsLBfq3' data-aid='StoryState__dropdown--label'>Unscheduled <img src='//assets.pivotaltracker.com/next/assets/next/aa0730f7-arrow-light.svg' alt=''></span></button></div></div><span class='state'><label data-aid='StateButton' data-destination-state='start' class='state button start' tabindex='-1'>Start</label></span></div></div>
						  <div class='reviews'><div class='Reviews___3RL2ODu6' data-aid='Reviews'><div class='Reviews__controls___2HDGtk0b'><div class='Reviews__label___3eZCCaQO'>Reviews</div><div class='Dropdown'><div class='Dropdown__content' data-aid='Reviews__addReview'><button class='SMkCk__Button _3INnV__Button--default Dropdown__button Reviews__addReview___2qS8cLCf' aria-label='Reviews' type='button'><span class='Reviews__addReview--plus___1RlRoYng'>+</span><span>&nbsp;add review</span></button></div></div></div><div></div></div></div>
						 </div>

						<div class='story info_box'>
						  <div class='info'><div class='type row'>
			  <em>Story Type</em>
			  <div class='dropdown story_type'>
			  
				<input aria-hidden='true' type='hidden' name='story[story_type]' value='feature'>
			  
			  <input aria-hidden='true' type='text' id='story_type_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
			  <a id='story_type_dropdown_$cid' class='selection item_feature' tabindex='-1'><span>feature</span></a>

			  
				<a id='story_type_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
			  

			  <section>
				<div class='dropdown_menu search'>
				  
					
					  <div class='search_item'><input aria-label='search' type='text' id='story_type_dropdown_$cid_search' class='search'></div>
					
				  

				  <ul>
					
					  <li class='no_search_results hidden'>No results match.</li>
					
					
					  <li data-value='feature' data-index='1' class='dropdown_item selected'><a class='item_feature ' id='feature_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>feature</span></a></li>
					
					  <li data-value='bug' data-index='2' class='dropdown_item'><a class='item_bug ' id='bug_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>bug</span></a></li>
					
					  <li data-value='chore' data-index='3' class='dropdown_item'><a class='item_chore ' id='chore_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>chore</span></a></li>
					
					  <li data-value='release' data-index='4' class='dropdown_item'><a class='item_release ' id='release_story_type_dropdown_$cid' href='#'><span class='dropdown_label'>release</span></a></li>
					
				  </ul>
				</div>
			  </section>
			</div>

			</div>



			<div class='estimate row'>
			  <em>Points</em>
			  <div class='dropdown story_estimate'>
			  
				<input aria-hidden='true' type='hidden' name='story[estimate]' value='0' data-type='number'>
			  
			  <input aria-hidden='true' type='text' id='story_estimate_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
			  <a id='story_estimate_dropdown_$cid' class='selection item_0' tabindex='-1'><span>0 points</span></a>

			  
				<a id='story_estimate_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
			  

			  <section>
				<div class='dropdown_menu search'>
				  
					
					  <div class='search_item'><input aria-label='search' type='text' id='story_estimate_dropdown_$cid_search' class='search'></div>
					
				  

				  <ul>
					
					  <li class='no_search_results hidden'>No results match.</li>
					
					
					  <li data-value='-1' data-index='1' class='dropdown_item'><a class='item_-1 ' id='-1_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>unestimated</span></a></li>
					
					  <li data-value='0' data-index='2' class='dropdown_item selected'><a class='item_0 ' id='0_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>0 points</span></a></li>
					
					  <li data-value='1' data-index='3' class='dropdown_item'><a class='item_1 ' id='1_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>1 point</span></a></li>
					
					  <li data-value='2' data-index='4' class='dropdown_item'><a class='item_2 ' id='2_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>2 points</span></a></li>
					
					  <li data-value='3' data-index='5' class='dropdown_item'><a class='item_3 ' id='3_story_estimate_dropdown_$cid' href='#'><span class='dropdown_label'>3 points</span></a></li>
					
				  </ul>
				</div>
			  </section>
			</div>

			</div>


			<div class='requester row'>
			  <em>Requester</em>
			  <div class='dropdown story_requested_by_id'>
			  
				<input aria-hidden='true' type='hidden' name='story[requested_by_id]' value='2936271' data-type='number'>
			  
			  <input aria-hidden='true' type='text' id='story_requested_by_id_dropdown_$cid_honeypot' tabindex='0' class='honeypot'>
			  <a id='story_requested_by_id_dropdown_$cid' class='selection item_2936271' tabindex='-1'><span><div class='name hbsAvatarName'>Scott Jenkins</div>
				<span class='selectable_owner_row_element hbsAvatar__container requester_link' data-person-id='2936271' tabindex='-1'><div class='hbsAvatar hbsAvatar__hasHoverCard' data-person-id='2936271'><span><span class='hbsAvatar__initials'>SJ</span></span></div></span></span></a>

			  
				<a id='story_requested_by_id_dropdown_$cid_arrow' class='arrow target' tabindex='-1'></a>
			  

			  <section>
				<div class='dropdown_menu search'>
				  
					
					  <div class='search_item'><input aria-label='search' type='text' id='story_requested_by_id_dropdown_$cid_search' class='search'></div>
					
				  

				  <ul>
					
					  <li class='no_search_results hidden'>No results match.</li>
					
					
					  <li data-value='2936271' data-index='1' class='dropdown_item selected'><a class='item_2936271 ' id='2936271_story_requested_by_id_dropdown_$cid' href='#'><span class='dropdown_description'>SJ</span><span class='dropdown_label'>Scott Jenkins</span></a></li>
					
				  </ul>
				</div>
			  </section>
			</div>

			</div>

			<div class='owner row'>
			  <em>Owners</em>
			  <div class='story_owners'>
			  <input aria-hidden='true' type='text' id='story_owner_ids_$cid_honeypot' tabindex='0' class='honeypot'>
			  <a id='add_owner_$cid' class='selectable_owner_row_element add_owner has_owners' tabindex='-1'>
				
			  </a>
			  
				<span class='selectable_owner_row_element hbsAvatar__container owner_link selected' data-person-id='2936271' tabindex='-1'><span class='wrapper hbsAvatarName'><span class='name'>Scott Jenkins</span></span><div class='hbsAvatar hbsAvatar__hasHoverCard' data-person-id='2936271'><span><span class='hbsAvatar__initials'>SJ</span></span></div></span>
			  
			</div>

			</div>
			</div>
						  <div class='integration_wrapper'>
			  

			</div>
						  <div class='followers_wrapper'><div class='following row' role='group' aria-label='followers'>
			  
				<em>Follow this story</em>
			  
			  <input type='hidden' name='story[following]' value='0'>
			  
				<input type='checkbox' id='$cid_following' aria-label='follow this story' checked='checked' disabled='true' class='autosaves std value' name='story[following]' value='on'>
			  
			  <span class='count not_read_only' data-cid='$cid'>1 follower</span>
			</div>
			</div>
						  
							<div class='row timestamp_wrapper'>
							  <div class='timestamp'>
							    <div class='timestamps clickable'>
								  <div class='saving timestamp_row'><span>Saving...</span></div>
								  <div class='updated_at timestamp_row'>Updated: <span data-millis='1560859061000'>18 Jun 2019, 6:57am</span></div>
								  <div class='requested_at timestamp_row'>Requested: <span data-millis='1498652166000'>28 Jun 2017, 7:16am</span></div>
								</div>
							  </div>
							</div>						  
						</div>
					  </div>


					  <div class='mini attachments'></div>
					</div>
				  </aside>
				</form>
			  </section>
			  <section class='blockers full'><div><div data-aid='Blockers'><h4>Blockers</h4><div class='BlockerShow___1hFt8_I1' data-aid='BlockerShow'><button class='BlockerShow__toggleButton___2GFmLA4H' data-aid='BlockerShow__toggleButton' data-focus-id='BlockerShow__toggleButton--c137' title='Resolve this blocker'></button><div tabindex='0' class='BlockerShow__description___3LsV-EfY' data-aid='BlockerShow__description'><span class='tracker_markup'><p>dddddd</p></span></div><div class='BlockerShow__controls___2bTZp0xk'><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='BlockerShow__resolveButton' aria-label='Resolve blocker'><span class='iconClassName' title='Resolve blocker' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/5431490a-checkMark-thin.svg&quot;) center center no-repeat;'></span></button><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='BlockerShow__deleteButton' aria-label='Delete blocker'><span class='' title='Delete blocker' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/6f796a82-trashcan.svg&quot;) center center no-repeat;'></span></button></div></div><div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='BlockerAdd' data-focus-id='BlockerAdd'><span class='AddSubresourceButton__icon___h1-Z9ENT'></span><span class='AddSubresourceButton__message___2vsNCBXi'>Add blocker or impediment</span></div></div></div></section>
			  <section class='blocking full'></section>
			  <section class='description full'><div data-aid='Description' class='Description___3oUx83yQ'><h4 id='description$cid'>Description</h4><div class='DescriptionShow___3-QsNMNj DescriptionShow__placeholder___1NuiicbF' tabindex='0' data-aid='renderedDescription' data-focus-id='DescriptionShow--$cid'>Add a description</div></div></section>
			  <section class='labels_container full'>
				<div id='story_labels_$parent_cid' class='labels'><div class='StoryLabelsMaker___Lw8q4VmA'><h4>Labels</h4><div class='StoryLabelsMaker__container___2B23m_z1'><div data-aid='StoryLabelsMaker__contentContainer' class='StoryLabelsMaker__contentContainer___3CvJ07iU'><div class='Label___mHNHD3zD' data-aid='Label--c105--$cid' data-focus-id='Label--c105--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>juxtaposition</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c108--$cid' data-focus-id='Label--c108--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>mechanical</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c114--$cid' data-focus-id='Label--c114--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>sophisticated</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='Label___mHNHD3zD' data-aid='Label--c120--$cid' data-focus-id='Label--c120--$cid' tabindex='-1'><div class='Label__Name___mTDXx408' data-aid='Label__Name'>wonder</div><div class='Label__RemoveButton___2fQtutmR' data-aid='Label__RemoveButton'></div></div><div class='LabelsSearch___2V7bl828' data-aid='LabelsSearch'><div class='tn-text-input___1CFr3eiU LabelsSearch__container___kJAdoNya'><div><input autocomplete='off' class='tn-text-input__field___3gLo07Il tn-text-input__field--medium___v3Ex3B7Z LabelsSearch__input___3BARDmFr' type='text' placeholder='' data-aid='LabelsSearch__input' data-focus-id='LabelsSearch--$cid' aria-label='Search for an existing label or type a new label' value=''></div></div></div></div><a class='StoryLabelsMaker__arrow___OjD5Om2A' data-aid='StoryLabelsMaker__arrow'></a></div></div></div>
			  </section>
			  <section class='code full' data-aid='code'><div data-aid='Code' class='Code___3pLWnu1D'><h4 class='Code__heading___2LJTrLuO'><a href='/help/articles/github_integration' target='_blank' class='Code__menuHelp___3NHpSmo9'>Code</a></h4><input data-aid='GitHubAttach__input' aria-label='GitHub Paste Link' class='GitHubAttach__input___3-hGhNzg' type='text' placeholder='Paste link to pull request or branch...' value=''></div></section>
			  <section class='tasks full'><div><div data-aid='Tasks'><span class='tasks_count' data-aid='taskCounts'><h4>Tasks (0/1)</h4></span><div><div class='TaskShow___2LNLUMGe' data-aid='TaskShow' draggable='true'><input type='checkbox' title='mark task complete' data-aid='toggle-complete' data-focus-id='TaskShow__checkbox--c136' class='TaskShow__checkbox___2BQ9bNAA'><div tabindex='0' class='TaskShow__description___3R_4oT7G tracker_markup' data-aid='TaskDescription'><span class='tracker_markup'><p>jjj</p></span></div><nav class='TaskShow__actions___3dCdQMej undefined TaskShow__actions--unfocused___3SQSv294'><button class='IconButton___2y4Scyq6 IconButton--small___3D375vVd' data-aid='delete' aria-label='Delete'><span class='' data-click-aid='delete' title='Delete' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/6f796a82-trashcan.svg&quot;) center center no-repeat;'></span></button></nav></div><div tabindex='0' class='AddSubresourceButton___2PetQjcb' data-aid='TaskAdd' data-focus-id='TaskAdd'><span class='AddSubresourceButton__icon___h1-Z9ENT'></span><span class='AddSubresourceButton__message___2vsNCBXi'>Add a task</span></div></div></div></div></section>
			  <section class='activity full'><div><div class='Activity___2ZLT4Ekd Activity--sequential___snOLHrxL'><div class='Activity__header___2pU2Tw9L'><h4 class='Activity__title___2uuNQeA8 tn-comments__activity'>Activity</h4><div class='ToggleComment__Container___eOafaqW5'><span class='ToggleHeading___1K1l1zUE'>Sort by</span><span data-aid='ToggleComment' class='ToggleComment___yucMHq3w' role='button'><span data-aid='ToggleStatus' class='ToggleStatus___34uUfSHP' tabindex='0'>Oldest to newest</span></span></div></div><ol class='comments all_activity' data-aid='comments'><li class='item___3FqFqgaA'><div class='GLOBAL__activity comment CommentEdit___3nWNXIac CommentEdit--new___3PcQfnGf' tabindex='-1' data-aid='comment-new'><div class='CommentEdit__writePreview-bar___1aXEb92m'><div><button class='CommentEdit__tab___qUF4n2tB' data-aid='WriteComment'>Write</button><button class='CommentEdit__tab___qUF4n2tB CommentEdit__tab--disabled___2C0MLjfb' data-aid='PreviewComment'>Preview</button></div><a href='/help/markdown' class='CommentEdit__markdown_help___lvuA4kSr' target='_blank' tabindex='0' title='Markdown help' data-focus-id='FormattingHelp__link--$cid'>Formatting help</a></div><div class='CommentEdit__commentBox___21QXi4py'><div class='CommentEdit__textContainer___2V0EKFmS'><div data-aid='CommentGutter' class='CommentGutter___1wlvO_PP'><div><div data-aid='Avatar' class='Avatar Avatar--initials'>SJ</div></div></div><div class='CommentEdit__preview___2yY8VPnu'><span class='tracker_markup'><p>Preview your <a href='/help/markdown' target='_blank' rel='noopener' tabindex='-1'>Markdown formatted</a> text here.</p></span></div><div class='CommentEdit__textEditor___3L0zZts-' data-aid='CommentV2TextEditor'><div class='MentionableTextArea___1zoYeUDA'><div class='AutosizeTextarea___2iWScFt6'><div class='AutosizeTextarea__container___31scfkZp'><textarea id='comment-edit-$cid' aria-label='Comment' data-aid='Comment__textarea' data-focus-id='CommentEdit__textarea--$cid' class='AutosizeTextarea__textarea___1LL2IPEy tracker_markup MentionableTextArea__textarea___2WDXl0X6 CommentEdit__textarea___2Rzdgkej' placeholder='Add a comment or paste an image'></textarea></div><div aria-hidden='true' class='AutosizeTextarea__shadowClass___34L-ruqt tracker_markup MentionableTextArea__textarea___2WDXl0X6 CommentEdit__textarea___2Rzdgkej'><span></span><span>w</span></div></div></div></div></div><div class='CommentEdit__action-bar___3dyLnEWb'><div class='CommentEdit__button-group___2ytpiQPa'><button class='SMkCk__Button QbMBD__Button--primary _3olWk__Button--small undefined _3Xvsn__Button--disabled' disabled='' data-aid='comment-submit' type='button'>Post comment</button></div><div class=''><span class='CommentEditToolbar__container___3LKaxfw8' data-aid='CommentEditToolbar__container'><div class='CommentEditToolbar__action___3t8pcxD7'><button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-mention' aria-label='Mention person in comment'><span class='' title='Mention person in comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/8846f168-mention.svg&quot;) center center no-repeat;'></span></button></div><div class='CommentEditToolbar__action___3t8pcxD7'><a class=''><div data-aid='attachmentDropdownButton' tabindex='0' title='Add attachment to comment' class='DropdownButton__icon___1qwu3upG CommentEditToolbar__attachmentIcon___48kfJPfH' aria-label='Add attachment'></div></a><input data-aid='CommentEditToolbar__fileInput' type='file' title='Attach file from your computer' name='file' multiple='' tabindex='-1' style='display: none;'></div><div class='CommentEditToolbar__action___3t8pcxD7'><button class='IconButton___2y4Scyq6 IconButton--borderless___1t-CE8H2 IconButton--inverted___2OWhVJqP IconButton--opaque___3am6FGGe' data-aid='add-emoji' aria-label='Add emoji to comment'><span class='' title='Add emoji to comment' style='background: url(&quot;https://assets.pivotaltracker.com/next/assets/next/2b4b3f66-emoji-light.svg&quot;) center center no-repeat;'></span></button></div></span></div></div></div></div></li></ol></div></div></section>
			</section>
		</div>
	</div>
</div>
";
echo $form;