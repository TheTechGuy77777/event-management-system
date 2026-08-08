<?php $jobs = DB::table("failed_jobs")->get(); foreach ($jobs as $j) { echo "=== " . $j->uuid . " ===" . PHP_EOL; echo substr($j->exception, 0, 800) . PHP_EOL . PHP_EOL; }
