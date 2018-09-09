<div class="block-header">
	<h2>DATA KANTOR CABANG</h2>
</div>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="body">
				<?php

				
				// $q = "select * from data where kacab=?";
				// $options = [
				//     'bindings' => ['Pos Bukitraya']
				// ];
				// $job->extract('Query', $q,$options)->transform('JsonEncode');

				$q = "select * from data";
				
				$job->extract('Query', $q)->transform('JsonEncode');

				$loader->load('data', $job);
				
				echo "<pre>";
				print_r($job);
				print_r($loader);
				echo "</pre>";

				?>
			</div>
		</div>
	</div>
</div>