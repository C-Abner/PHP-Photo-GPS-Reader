<?php
$info = exif_read_data('/home/temp/aaa.jpg', 0, true);

$lat = getGps($info['GPS']['GPSLatitude']);
$lon = getGps($info['GPS']['GPSLongitude']);
$lat_ref = refCheck($info['GPS']['GPSLatitudeRef']);
$lon_ref = refCheck($info['GPS']['GPSLongitudeRef']);

$lat_s = $lat['degrees'] * $lat_ref . " " . $lat['minutes'] . " " . $lat['seconds'];
$lon_s = $lon['degrees'] * $lon_ref . " " . $lon['minutes'] . " " . $lon['seconds'];

print_r($lat_s);
print_r($lon_s);
echo "http://maps.google.com.tw/?q=" . $lat_s . "," . $lon_s;

function getGps($exifCoord)
{
	$degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
	$minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
	$seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;
	//normalize
	$minutes += 60 * ($degrees - floor($degrees));
	$degrees = floor($degrees);
	$seconds += 60 * ($minutes - floor($minutes));
	$minutes = floor($minutes);
	//extra normalization, probably not necessary unless you get weird data
	if($seconds >= 60)
	{
		$minutes += floor($seconds/60.0);
		$seconds -= 60*floor($seconds/60.0);
	}
	if($minutes >= 60)
	{
		$degrees += floor($minutes/60.0);
		$minutes -= 60*floor($minutes/60.0);
	}
	return array('degrees' => $degrees, 'minutes' => $minutes, 'seconds' => $seconds);
}
function gps2Num($coordPart)
{
	$parts = explode('/', $coordPart);
	if(count($parts) <= 0)// jic
		return 0;
	if(count($parts) == 1)
		return $parts[0];
	return floatval($parts[0]) / floatval($parts[1]);
}
function refCheck($ref){
	if ($ref == 'S' or $ref == 'W') return -1;
	return 1;
}
?>

