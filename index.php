<?php
require 'vendor/autoload.php';

use BoyHagemann\Wave\Wave;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$filename = $_FILES['file_name']['tmp_name'];
	$wave = new Wave();

$wave->setFilename($filename);
// Assuming we already analyzed the wave...
$data = $wave->getWaveformData();
$waveformHtml = '';

	
	$stepNotFound = true;
	$step = 1;
	
	// while ($stepNotFound === true) {
	$wave->setSteps(50);
	$data1 = $wave->getWaveformData();
	$channel1 = $data1->getChannels()[0];
	$amplitudes1 = $channel1->getValues();
	$amplitudeCount1 = count($amplitudes1);

		// if($amplitudeCount1 < 2800) {
		// 	$stepNotFound = false;
		// }

		// $step += 10;
	// }
	
	
	$maxAmplitude = max($amplitudes1);
	$maxPerentage = 0;
	$percentages = [];
	$count =  0;
	foreach($amplitudes1 as $data) {
		$percentage = $data / $maxAmplitude;
		if($percentage > 0.2 || $percentage === 0) {
			$percentage = $percentage * 0;
		} else {
			$percentage = $percentage * 200;
			if($maxPerentage < $percentage) {
				$maxPerentage = $percentage;
			}
			$percentages[] = $percentage;
			$count ++;
			if($count > 1300) {
				break;
			}
		}
	}
	
	foreach($percentages as $p) {
		$waveformHtml .= "<span class='Aligner-item' style='height:". $p*100/$maxPerentage ."%'></span>";
	}
}

?>
<?php if($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
<html>
    <head>        
        <style>

        #waveform {
            float: left;            
            position: relative;
            height: 400px;
            width: 1400px;
        }
        span {
            
            display: inline-block;
            width: 1px;
            background: #ddd;
        }
				.Aligner {
					display: flex;
					align-items: center;
					justify-content: center;
				}

				.Aligner-item {
					max-width: 50%;
				}

				.Aligner-item--top {
					align-self: flex-start;
				}

				.Aligner-item--bottom {
					align-self: flex-end;
				}

        </style>
    </head>
    <body>        
        
        <div id="waveform" class="Aligner">
            <?php echo $waveformHtml ?>
        </div>
        
    </body>
</html>
<?php } else { ?>
<form  enctype="multipart/form-data" method="post">
	<input type='file' name="file_name">
	<br>
	<input type='submit'>
</form>
</body>
</html>
<?php } ?>