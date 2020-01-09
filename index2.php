<?php
require_once 'Query.php';
require_once 'Domain.php';
require_once 'Client.php';
$domain = new Domain();
$client = new Client();
if (isset($_POST['showdns'])) 
{
	$clientId = $client->clientId($_POST['inputEmail']);
	$name = trim(strtolower($_POST['inputDomain']));
	$domain->setDomainName($name);
	$owner = true;
	if ($domain->checkName() && $clientId != NULL)
	{
		$domainId = $domain->domainIdByName();
		$domain->domainInfo($domainId);
		$res = json_decode($domain->getAnswer(), true);
		if ($res['result']['domain']['clientId'] == $clientId)
		{
			$info = $res;
			setcookie('domainId', $domainId);
			setcookie('clientId', $clientId);
		} else {
			$owner = false;
		}
	}
}
if (isset($_POST['changedns'])) 
{
	$inpArr = array($_POST['inputNS1'], $_POST['inputNS2'], $_POST['inputNS3'], $_POST['inputNS4']);
	for ($i = 0; $i < 4; $i++)
	{
		for ($j = $i; $j < 4; $j++)
		{
			if ($inpArr[$j] != NULL) 
			{
				$nservers[$i] = $inpArr[$j];
				break;
			}
		}
	}
	$domain->updateNameServers($_COOKIE['domainId'], $_COOKIE['clientId'], $nservers);
	$jsonstr = $domain->getAnswer();
	$keys = array_keys(json_decode($jsonstr, true));
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Project</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
</body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4 col-md-3 col-sm-3"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<form method="POST" style="margin-top:10px;">
					<div class="form-group">
						<label>Client Email:</label>
						<input type="text" class="form-control <?php if ($clientId == NULL) echo 'is-invalid'; ?>" name="inputEmail" placeholder="Enter Email" value="<?php if(isset($_POST['inputEmail'])) echo $_POST['inputEmail']; ?>">
						<?php if ($clientId == NULL) { ?>
							<span class="text-danger">No user with such Email</span>
						<?php } ?>
					</div>
					<div class="form-group">
						<label>Domain name:</label>
						<input type="text" class="form-control <?php if (!$domain->checkName()) echo 'is-invalid'; ?>" name="inputDomain" placeholder="Enter domain name" value="<?php if(isset($_POST['inputDomain'])) echo $_POST['inputDomain']; ?>">
						<?php if (!$domain->checkName()) { ?>
							<span class="text-danger">Incorrect domain name</span>
						<?php } ?>
					</div>
					<button type="submit" name="showdns" class="btn btn-primary">Show DNS</button>
					<div class="form-group">
					<?php if (!$owner) { ?>
							<span class="text-danger">Client is not an owner of the domain</span>
					<?php } ?>
					</div>
				</form>
				<form method="POST" style="margin-top:30px;">
					<div class="form-group">
						<label>NS:</label>
						<input type="text" class="form-control" name="inputNS1" placeholder="Enter NS" value="<?php if (0 < count($info['result']['domain']['nservers'])) echo $info['result']['domain']['nservers'][0]; ?>">
					</div>
					<div class="form-group">
						<label>NS:</label>
						<input type="text" class="form-control" name="inputNS2" placeholder="Enter NS" value="<?php if (1 < count($info['result']['domain']['nservers'])) echo $info['result']['domain']['nservers'][1]; ?>">
					</div>
					<div class="form-group">
						<label>NS:</label>
						<input type="text" class="form-control" name="inputNS3" placeholder="Enter NS" value="<?php if (2 < count($info['result']['domain']['nservers'])) echo $info['result']['domain']['nservers'][2]; ?>">
					</div>
					<div class="form-group">
						<label>NS:</label>
						<input type="text" class="form-control" name="inputNS4" placeholder="Enter NS" value="<?php if (3 < count($info['result']['domain']['nservers'])) echo $info['result']['domain']['nservers'][3]; ?>">
					</div>
					<button type="submit" name="changedns" class="btn btn-primary">Change DNS</button>
					<?php if ($keys[0] == 'id') { ?>
						<div class="form-group"style="margin-top:20px;">
							<span class="text-success" style="font-size:25px;">COMPLETE</span><br>
						</div>
					<?php } ?>
					<?php if ($keys[0] == 'error') { ?>
						<div class="form-group"style="margin-top:20px;">
							<span class="text-danger" style="font-size:25px;">ERROR</span><br>
							<span class="text-danger" style="font-size:20px;">JSON string: <?php echo $jsonstr; ?></span>
						</div>
					<?php } ?> 
				</form>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-3"></div>
		</div>
	</div>
</body>
</html>