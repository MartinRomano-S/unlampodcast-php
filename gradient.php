<?php 
$current = date('H:i');
$gradient = "background: linear-gradient(rgba(75,138,210,1) 0%, rgba(107,165,217,1) 50%, rgba(171,214,234,1) 100%);";

if(strtotime($current.":00")>strtotime('06:30:00') && strtotime($current.":00")<=strtotime('09:30:00')){
	//amanecer
	$gradient = "background: linear-gradient(rgba(118,178,183,1) 0%, rgba(136,175,173,1) 50%, rgba(216,154,130,1) 100%);";
} else { 

	if(strtotime($current.":00")>strtotime('09:30:00') && strtotime($current.":00")<=strtotime('18:00:00')){
		//mediodía
		$gradient = "background: linear-gradient(rgba(75,138,210,1) 0%, rgba(107,165,217,1) 50%, rgba(171,214,234,1) 100%);";
	} else {

		if(strtotime($current.":00")>strtotime('18:00:00') && strtotime($current.":00")<=strtotime('20:30:00')){
			//anochecer
			$gradient = "background: linear-gradient(rgba(69,41,99,1) 0%, rgba(210,129,126,1) 50%, rgba(179,60,40,1) 100%);";
		} else {

			if(strtotime($current.":00")>strtotime('20:30:00') && strtotime($current.":00")<=strtotime('06:30:00')){
				//noche
				$gradient = "background: linear-gradient(rgba(0,0,0,1) 0%, rgba(15,14,46,1) 50%, rgba(26,17,46,1) 100%);";
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Gradient Test</title>
</head>
<body style="color: white;">
<div style="text-align: center; margin: auto;height: 300px; width: 300px;background: linear-gradient(rgba(118,178,183,1) 0%, rgba(136,175,173,1) 50%, rgba(216,154,130,1) 100%);">
</div>
<div style="text-align: center; margin: auto; height: 300px; width: 300px;background: linear-gradient(rgba(75,138,210,1) 0%, rgba(107,165,217,1) 50%, rgba(171,214,234,1) 100%);">
</div>
<div style="text-align: center; margin: auto;height: 300px; width: 300px;background: linear-gradient(rgba(69,41,99,1) 0%, rgba(210,129,126,1) 50%, rgba(179,60,40,1) 100%);">
</div>
<div style="text-align: center; margin: auto;height: 300px; width: 300px;background: linear-gradient(rgba(0,0,0,1) 0%, rgba(15,14,46,1) 50%, rgba(26,17,46,1) 100%);">
</div>
</body>
</html>