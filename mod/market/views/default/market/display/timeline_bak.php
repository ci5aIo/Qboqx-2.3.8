<?php
$entity = $vars['entity'];
$section = $vars['this_section'];
$category = $entity->marketcategory;
$item_guid = $entity->guid;
setlocale(LC_MONETARY, 'en_US');
$fields = market_prepare_detailed_view_vars($entity);

/**/

$documents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'document',
	'relationship_guid' => $item_guid,
	'inverse_relationship' => true,
	'limit' => false,
	));
$contents = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'contents',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$contents = array_merge($contents, elgg_get_entities(array(
				'type' => 'object',
				'subtypes' => array('market', 'item'),
				'wheres' => array(
					"e.container_guid = $item_guid",
				),
			)));
$components = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$accessories = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => true,
	'limit' => false,
));
$parent_items = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'accessory',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));
$containers = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'relationship' => 'component',
	'relationship_guid' => $item_guid,
    'inverse_relationship' => false,
	'limit' => false,
));

//From 'mod\widget_manager\views\default\widgets\index_activity\content.php'
$widget = $vars["entity"];

$count = sanitise_int($widget->activity_count, false);
if (empty($count)) {
	$count = 10;
}

if ($activity_content = $widget->activity_content) {
	if (!is_array($activity_content)) {
		if ($activity_content == "all") {
			unset($activity_content);
		} else {
			$activity_content = explode(",", $activity_content);
		}
	}
}

$river_options = array(
	"pagination" => true,
	"limit" => $count,
	"object_guids" => $widget->guid,
	"type_subtype_pairs" => array()
);

if (empty($activity_content)) {
	$activity = elgg_list_river($river_options);
} else {
	foreach ($activity_content as $content) {
		list($type, $subtype) = explode(",", $content);
		if (!empty($type)) {
			$value = $subtype;
			if (array_key_exists($type, $river_options['type_subtype_pairs'])) {
				if (!is_array($river_options['type_subtype_pairs'][$type])) {
					$value = array($river_options['type_subtype_pairs'][$type]);
				} else {
					$value = $river_options['type_subtype_pairs'][$type];
				}
				
				$value[] = $subtype;
			}
			$river_options['type_subtype_pairs'][$type] = $value;
		}
	}
	
	$activity = elgg_list_river($river_options);
	$activity = elgg_get_river($river_options);
}

if (empty($activity)) {
	$activity = elgg_echo("river:none");
}

echo $activity;

/*****************************************************/
?>
<script type="text/javascript">
/*------------Validation Function-----------------*/
var count = 0; // To count blank fields.
function validation(event) {
var radio_check = document.getElementsByName('gender'); // Fetching radio button by name.
var input_field = document.getElementsByClassName('text_field'); // Fetching all inputs with same class name text_field and an html tag textarea.
var text_area = document.getElementsByTagName('textarea');
// Validating radio button.
if (radio_check[0].checked == false && radio_check[1].checked == false) {
var y = 0;
} else {
var y = 1;
}
// For loop to count blank inputs.
for (var i = input_field.length; i > count; i--) {
if (input_field[i - 1].value == '' || text_area.value == '') {
count = count + 1;
} else {
count = 0;
}
}
if (count != 0 || y == 0) {
alert("*All Fields are mandatory*"); // Notifying validation
event.preventDefault();
} else {
return true;
}
}
/*---------------------------------------------------------*/
// Function that executes on click of first next button.
function next_step1() {
document.getElementById("first").style.display = "none";
document.getElementById("second").style.display = "block";
document.getElementById("active2").style.color = "red";
}
// Function that executes on click of first previous button.
function prev_step1() {
document.getElementById("first").style.display = "block";
document.getElementById("second").style.display = "none";
document.getElementById("active1").style.color = "red";
document.getElementById("active2").style.color = "gray";
}
// Function that executes on click of second next button.
function next_step2() {
document.getElementById("second").style.display = "none";
document.getElementById("third").style.display = "block";
document.getElementById("active3").style.color = "red";
}
// Function that executes on click of second previous button.
function prev_step2() {
document.getElementById("third").style.display = "none";
document.getElementById("second").style.display = "block";
document.getElementById("active2").style.color = "red";
document.getElementById("active3").style.color = "gray";
}
</script>
<style>
@import "http://fonts.googleapis.com/css?family=Droid+Serif";  /* This Line is to import Google font style */
.content{
width:960px;
margin:20px auto
}
.main{
float:left;
width:650px;
margin-top:80px
}
#progressbar{
margin:0;
padding:0;
font-size:18px
}
#active1{
color:red
}
fieldset{
display:none;
width:350px;
padding:20px;
margin-top:50px;
margin-left:85px;
border-radius:5px;
box-shadow:3px 3px 25px 1px gray
}
#first{
display:block;
width:350px;
padding:20px;
margin-top:50px;
border-radius:5px;
margin-left:85px;
box-shadow:3px 3px 25px 1px gray
}
input[type=text],input[type=password],select{
width:100%;
margin:10px 0;
height:40px;
padding:5px;
border:3px solid #ecb0dc;
border-radius:4px
}
textarea{
width:100%;
margin:10px 0;
height:70px;
padding:5px;
border:3px solid #ecb0dc;
border-radius:4px
}
input[type=submit],input[type=button]{
width:120px;
margin:15px 25px;
padding:5px;
height:40px;
background-color:#a0522d;
border:none;
border-radius:4px;
color:#fff;
font-family:'Droid Serif',serif
}
h2,p{
text-align:center;
font-family:'Droid Serif',serif
}
li{
margin-right:52px;
display:inline;
color:#c1c5cc;
font-family:'Droid Serif',serif
}

</style>

<div class="content">
<!-- Multistep Form -->
<div class="main">
<form action="#" class="regform" method="get">
<!-- Progressbar -->
<ul id="progressbar">
<li id="active1">Create Account</li>
<li id="active2">Educational Profiles</li>
<li id="active3">Personal Details</li>
</ul>
<!-- Fieldsets -->
<fieldset id="first">
<h2 class="title">Create your account</h2>
<p class="subtitle">Step 1</p><input class="text_field" name="email" placeholder="Email" type="text" value="">
<input class="text_field" name="pass" placeholder="Password" type="password" value="">
<input class="text_field" name="cpass" placeholder="Confirm Password" type="password" value="">
<input id="next_btn1" onclick="next_step1()" type="button" value="Next">
</fieldset>
<fieldset id="second">
<h2 class="title">Educational Profiles</h2>
<p class="subtitle">Step 2</p>
<select class="options">
<option>--Select Education--</option>
<option>Post Graduate</option>
<option>Graduate</option>
<option>HSC</option>
<option>SSC</option>
</select>
<input class="text_field" name="marks" placeholder="Marks Obtained" type="text" value="">
<input class="text_field" name="pyear" placeholder="Passing Year" type="text" value="">
<input class="text_field" name="univ" placeholder="University" type="text">
<input id="pre_btn1" onclick="prev_step1()" type="button" value="Previous">
<input id="next_btn2" name="next" onclick="next_step2()" type="button" value="Next">
</fieldset>
<fieldset id="third">
<h2 class="title">Personal Details</h2>
<p class="subtitle">Step 3</p>
<input class="text_field" name="fname" placeholder="First Name" type="text">
<input class="text_field" name="lname" placeholder="Last Name" type="text">
<input class="text_field" name="cont" placeholder="Contact" type="text">
<label>Gender</label>
<input name="gender" type="radio" value="Male">Male
<input name="gender" type="radio" value="Female">Female
<textarea name="address" placeholder="Address">
</textarea>
<input id="pre_btn2" onclick="prev_step2()" type="button" value="Previous">
<input class="submit_btn" onclick="validation(event)" type="submit" value="Submit">
</fieldset>
</form>
</div>
</div>
<?php
