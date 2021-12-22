<?php
// https://phpdelusions.net/pdo
$database = new \PDO('sqlite:spider.db');
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$statement = $database->prepare("SELECT * FROM jobs WHERE uuid = ?");
$statement->execute([$_GET['jobid']]);
$job = json_decode($statement->fetch(PDO::FETCH_OBJ)->data);
$remainingTargets = $job->targets;
usort($remainingTargets, function ($a, $b) { return strcmp(md5($a), md5($b)); }); // a determinist shuffling

// Queue up to 30 target URLs we haven't already processed before
$multiHandle = curl_multi_init();
$targets = [];
while (count($targets) < 30 && count($remainingTargets) > 0) {
  $target = array_shift($remainingTargets);
  $statement = $database->prepare('SELECT * FROM spideredPages WHERE url = ?');
  $statement->execute([$target]);
  $page = $statement->fetch();
  if ($page !== false) {
    continue;
  }
	$curl = curl_init($target);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36');
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
  curl_multi_add_handle($multiHandle, $curl);
  $targets[$target] = $curl;
}

// Run loop for downloading
$running = null;
do {
  curl_multi_exec($multiHandle, $running);
} while ($running);

//var_dump("memory (MiB)", number_format(memory_get_usage()/1024/1024, 2), $_SERVER['REMOTE_PORT']);

// Harvest results
foreach ($targets as $target => $curl) {
  $html = curl_multi_getcontent($curl);
  curl_multi_remove_handle($multiHandle, $curl);
  $alreadyActivated = preg_match('/has already been activated/', $html);
  $statement = $database->prepare("REPLACE INTO spideredPages (url, date, data) VALUES (?, date('now'), ?)");
  $statement->execute([$target, json_encode($alreadyActivated)]); ///////////////////////////////////////uncomment
}
curl_multi_close($multiHandle);

if (count($remainingTargets) > 0) {
  header("refresh:2;?jobid={$_GET['jobid']}"); ///////////////////////////////////////////////uncomment
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <title>AHA eLearning Spider</title>
  </head>

  <body>
    <div class="jumbotron">
      <div class="container">
      <h1 class="display-3 fw-bold">AHA eLearning Spider &#x1F577;</h1>
        <p>Running report, <?= count($job->targets) - count($remainingTargets) ?> of <?= count($job->targets) ?> pages retrieved...</p>

        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?= 100 * (count($job->targets) - count($remainingTargets)) / count($job->targets) ?>%"></div>
        </div>
      </div>
    </div>

    <div class="container">
      
      <?php
if (count($remainingTargets) > 0)	{
  exit;
}

echo '<table class="table">';
foreach ($job->targets as $target) {
  $statement = $database->prepare('SELECT * FROM spideredPages WHERE url = ?');
  $statement->execute([$target]);
  $pageData = json_decode($statement->fetch(PDO::FETCH_OBJ)->data);
  echo '<tr>';
  echo '<td>' . htmlspecialchars($target);
  echo '<td>' . htmlspecialchars(preg_replace('/.*course=([0-9]+).*/', '$1', $target));
  echo '<td>' . ($pageData == 1 ? 'Used' : 'Unused');
	flush();
}
echo '</table>';
?>

    </div>
  </body>
</html>
