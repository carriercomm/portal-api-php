<?php

require_once('../src/ColoCrossing.php');

$colocrossing_client = new ColoCrossing_Client();
$colocrossing_client->setAPIToken('eb3ac813fd85acd1a386c46cd84e39c6bf263c35');
$colocrossing_client->setOption('ssl_verify', false);

?>

<h1>Control a Switch Port</h1>

<?php

$switch_id = 38; //Enter your switch id here
$switch = $colocrossing_client->devices->find($switch_id);

if (isset($switch) && $switch->getType()->isNetworkDistribution())
{
	$port_id = 4;//Enter your port id here
	$port = $switch->getPort($port_id);

	if (isset($port) && $port->isControllable())
	{
		$success = $port->turnOn();
		//$success = $port->turnOff();

		echo '<p>Success? ' . ($success ? 'Yes' : 'No') . '</p>';
	}
}

?>
