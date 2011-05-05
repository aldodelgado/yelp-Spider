<?
// Example URL
// http://api.yelp.com/business_review_search?term=grocery&location=33015&radius=25&ywsid=XCTc9Cqg5V62V_88siju0A&category=grocery

/* Config For this API */
$url 								= 'http://api.yelp.com/business_review_search';
$term								= 'grocery';
$radius							= '25';
$ywsid 							= 'XCTc9Cqg5V62V_88siju0A';
$category						= 'grocery';
$num_biz_requested 	= '100';
$location						= array(
	'33122',
	'32615',
	'32616',
	'32816',
	'32820',
	'32825',
);


$file = fopen('zips.csv', 'r');
$zips = array();
while (($line = fgetcsv($file)) !== FALSE) {
  $new_zips = explode("\r",$line[0]);
  $zips = array_merge($zips, $new_zips);
}

fclose($file);

/* Magic Poop */
$stores_file = fopen('stores.csv', 'w+');

foreach ($zips as $zip) {
		// Build URL
    $full_url						= $url . "?term" . $term . "&location=" . $zip . "&ywsid=" . $ywsid . "&category=" . $category;
    
    // Run URL
    $yelpstring = file_get_contents($full_url, true);
    
    // Decode JSON from yelp
    $obj = json_decode($yelpstring);	
   
		/* Loops thourgh the results and prints them to the screen */
		foreach($obj->businesses as $business):
		$biz = array();
		echo '<pre>';print_r($business);
		$biz['name'] = $business->name;
		$biz['address'] = $business->address1;
		$biz['city'] = $business->city;
		$biz['state'] = $business->state;
		$biz['zip'] = $business->zip;
		$biz['phone'] = $business->phone;

		print_r($business);
		print_r($biz);
			fputcsv($stores_file, $biz, ',');
		
			echo "<img border=0 src='".$business->photo_url."'><br/>";
			echo $business->name."<br />";
			echo $business->address1."<br />";

			if( $business->address2 ){
				echo $business->address2."<br />";
			};

			echo $business->city ."<br />";
			echo $business->state ."<br />";
			echo $business->zip ."<br />";

			echo $business->phone ."<br />";

			echo $business->latitude ."<br />";
			echo $business->longitude ."<br />";

			echo "<hr>";
			endforeach; 


};
fclose($stores_file);

/*
function objectToArray( $object ) {
	if( !is_object( $object ) && !is_array( $object ) ) {
		return $object;
	}
	if( is_object( $object ) ) {
		$object = get_object_vars( $object );
	}
	
	return array_map( 'objectToArray', $object );
}

$array = objectToArray( $obj->businesses );

$fp = fopen('file.csv', 'w');

foreach ($obj->businesses as $business) {
	fputcsv($fp, $business, ',');
}

fclose($fp);
*/

?>