<?php

//ENTER YOUR SUBMITTABLE ACCOUNT EMAIL
$user = "youremail@yourhost.com";

//ENTER YOUR SUBMITTABLE ACCOUNT API ACCESS TOKEN 
$password = "your Submittable API Token";

//*****************************
//DO NOT CHANGE BELOW THIS LINE
//*****************************
$sort_by = $_GET['sort_by'];
$sort_dir = $_GET['sort_dir'];

if(isset($_GET['desired_width'])){
	$desired_width = $_GET['desired_width'];
}else{
	$desired_width = 100;
}

$num_pages = $_GET['num_pages'];
$subs_per_page = $_GET['subs_per_page'];

if(isset($_GET['sort_by']) && isset($_GET['sort_dir']) && isset($_GET['subs_per_page']) && isset($_GET['num_pages'])){
	$url = 'https://api-stage.submittable.com/v1/submissions?sort='.$_GET['sort_by'].'&dir='.$_GET['sort_dir'].'&count='.$_GET['subs_per_page'].'&page='.$_GET['num_pages'];
}else{
	$url = 'https://api-stage.submittable.com/v1/submissions';
}

$context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode("$user:$password"))));
$rownum = 0;
$y = 0;

$data = file_get_contents($url, false, $context);
$json = json_decode($data, true);
$total_items = $json['total_items'];
$items_per_page = $json['items_per_page'];
$total_pages = $total_items / $items_per_page;
$total_pages = ceil($total_pages);

echo '<h2 style="text-align:center">Submittable Image Gallery</h2>';
echo '<form action="'.$_SERVER['PHP_SELF'].'" method="get">';
echo'<div style="width:100%">';
echo '<div style="width:20%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
echo 'Sort by: <select name="sort_by">';
for ($x=0; $x<=2; $x++)
{
  switch ($x)
	{
	case 0:
	$sort_val = "category";
	$sort_disp = "Category";
	break;
	case 1:
	$sort_val = "submitted";
	$sort_disp = "Date Submitted";
	break;
	case 2:
	$sort_val = "submitter";
	$sort_disp = "Submitter";
	break;
	}
	echo '<option value="'.$sort_val.'"';
	
	if($sort_by == $sort_val) {
		echo ' selected="selected"';
	}
	
	echo '>'.$sort_disp.'</option>';
}

echo '</select>';
echo '</div>';
echo '<div style="width:20%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
echo 'Sort direction: <select name="sort_dir">';
for ($x=0; $x<=1; $x++)
{
  switch ($x)
	{
	case 0:
	$sort_dir_val = "asc";
	$sort_disp_val = "Forwards";
	break;
	case 1:
	$sort_dir_val = "desc";
	$sort_disp_val = "Backwards";
	break;
	}
	echo '<option value="'.$sort_dir_val.'"';
	
	if($sort_dir == $sort_dir_val) {
		echo ' selected="selected"';
	}
	
	echo '>'.$sort_disp_val.'</option>';
}

echo '</select>';
echo '</div>';
echo '<div style="width:20%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
echo 'Image width: <select name="desired_width">';
for ($x=0; $x<=2; $x++)
{
  switch ($x)
	{
	case 0:
	$width_val = "100";
	$width_disp_val = "100 px";
	break;
	case 1:
	$width_val = "200";
	$width_disp_val = "200 px";
	break;
	case 2:
	$width_val = "300";
	$width_disp_val = "300 px";
	break;
	}
	echo '<option value="'.$width_val.'"';
	
	if($desired_width == $width_val) {
		echo ' selected="selected"';
	}
	
	echo '>'.$width_disp_val.'</option>';
}

echo '</select>';
echo '</div>';
echo '<div style="width:20%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
echo 'Submissions per page: <select name="subs_per_page">';
for ($x=10; $x>=1; $x--)
{
	echo '<option value="'.$x.'"';
	
	if($x == $subs_per_page) {
		echo ' selected="selected"';
	}
	
	echo '>'.$x.'</option>';
}

echo '</select>';
echo '</div>';
echo '<div style="width:20%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
echo 'Page number: <select name="num_pages">';
for ($x=1; $x<=$total_pages; $x++)
{
	echo '<option value="'.$x.'"';
	
	if($num_pages == $x) {
		echo ' selected="selected"';
	}
	
	echo '>'.$x.'</option>';
}

echo '</select>&nbsp;&nbsp;&nbsp;<input name="btnSubmit" type="submit" value="Get Images" />';
echo '</div>';
echo '<br style="clear:both"/>';


$strSubmissions = '<div style="width:100%">';
foreach($json as $key => $value) {
    if (is_array($value)){
		foreach ($value as $each_member) {
    		while (list($key1, $value1) = each ($each_member)) {				
				switch ($key1) {
					case "files":
						foreach($value1 as $key2 => $value2) {
							foreach($value2 as $key3 => $value3) {
								switch ($y) {
									case 1:
										$file_name = $value3;
									break;
									case 2:
										$file_extension = $value3;
									break;
									case 5:
										$file_url = $value3;
										if ($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif") {
											$strSubmissions .= '<div style="width:25%;float:left;text-align:center;margin-top:10px;margin-bottom:10px">';
											$strSubmissions .= '<a href="thumbnail.php?user='.urlencode($user).'&password='.urlencode($password).'&url='.urlencode($file_url).'&file_extension='.urlencode($file_extension).'" target="_blank">';
											$strSubmissions .= '<img src="thumbnail.php?user='.urlencode($user).'&password='.urlencode($password).'&url='.urlencode($file_url).'&desired_width='.urlencode($desired_width).'&file_extension='.urlencode($file_extension).'" border="0" />';
											$strSubmissions .= '</a><br />';
											$strSubmissions .= '<span style="font-size: 0.75em">'.$file_name.'</span>';
											$strSubmissions .= '</div>';
											$rownum++;
											if($rownum % 4 == 0) {
												$strSubmissions .= '<br style="clear:both"/>';
											}
										}
									break;
								}
								if($y % 6 == 0) {
									$y = 0;
								}
								$y++;
							}
						}
					break;
				}
			}
		}
    }
}

$strSubmissions .= '</div>';
echo $strSubmissions;
?>
