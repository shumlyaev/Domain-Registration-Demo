<?php
require_once 'Query.php';
require_once 'Domain.php';
require_once 'Client.php';
require_once 'Register.php';
$domain = new Domain();
$client = new Client();
if(isset($_POST['createDomain']))
{
	$domain->setDomainName($name);
	$clientId = $client->clientId($_POST['inputEmail']);
	$period = $_POST['selectPeriod'];
	$noCheck = true;
	if (isset($_POST['whois']))
		$noCheck = false;
	$name = trim(strtolower($_POST['inputDomain']));
	$domain->setDomainName($name);
	if ($domain->checkName() && $clientId != NULL)
	{
		$domain->domainCreate($clientId, $period, $noCheck);
		$output = json_decode($domain->getAnswer(), true);
		$keys = array_keys($output);
	}
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
					<div class="form-group">
						<label>Period (years):</label>
						<select class="form-control" name="selectPeriod">
						<?php
						$regInfo = new Register();
						$regInfo->registryInfo();
						$res = json_decode($regInfo->getAnswer(), true);
						$periodsArr = $res['result']['registry']['registerPeriods'];
							for ($i = 0; $i < count($periodsArr); $i++)
								echo '<option value="'. $periodsArr[$i] .'">'. $periodsArr[$i] .'</option>';
						?>
						</select>
					</div>
					<div class="form-group">
						<label><input type="checkbox" name="whois" <?php if (isset($_POST['whois'])) echo 'checked'; ?>> Don't use whois</label>
					</div>
						<button type="submit" name="createDomain" class="btn btn-primary">Create domain</button>
					<?php if ($keys[0] == 'id') { ?>
						<div class="form-group"style="margin-top:20px;">
							<span class="text-success" style="font-size:25px;">COMPLETE</span><br>
						</div>
					<?php } ?>
					<?php if ($keys[0] == 'error') { ?>
						<div class="form-group"style="margin-top:20px;">
							<span class="text-danger" style="font-size:25px;">ERROR</span><br>
							<span class="text-danger" style="font-size:20px;">Message: <?php echo $output['error']['message']; ?></span><br>
							<span class="text-danger" style="font-size:20px;">JSON string: <?php echo $domain->getAnswer(); ?></span>
						</div>
					<?php } ?> 
				</form>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-3"></div>
		</div>
	</div>
</body>
</html> 