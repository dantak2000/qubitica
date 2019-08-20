<?php

function hex_to_dec($input)
	{
		if(substr($input, 0, 2) == '0x')
			$input = substr($input, 2);

		if(preg_match('/[a-f0-9]+/', $input))
			return hexdec($input);

		return $input;
	}

function dec_to_hex($input)
	{
    $hex = dechex($input);
    //$hex = '0x' . sprintf('%064d', $input);

		return $hex;
	}

//MySQL DB
$PWD="";
$conn = new mysqli("localhost", "user", "PWD", "TABLE");

require_once "vendor/autoload.php";

use kornrunner\Keccak;
use Web3p\EthereumUtil\Util;


$util = new Util;


$treffer_array=array();

////////////////////pattern- install new function
for ($m=0;$m<64;$m++)
{

$muster=dec_to_hex($m);
$musterlong=strlen($muster);

for( $i=33; $i<=42-$musterlong; $i++ )
{
$musterblank="0000000000000000000000000000000000000000000000000000000000000000";
//$musterblank="FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF00000000000000000000000000000000";
for ($k=0; $k<$musterlong;$k++)
   {
   $musterblank[$i+$k]= $muster[$k];
   }

if ($musterblank=="0000000000000000000000000000000000000000000000000000000000000000"){continue;}

$private_key=$musterblank;
$public_key=$util->privateKeyToPublicKey($private_key);
$public_address=$util->publicKeyToAddress($public_key);

$public_address = substr($public_address,2);



//find in DB
$sql = "select * from accounts where hash=lower('$public_address')";
//echo $sql."\n";
$result = $conn->query($sql);

if ($result->num_rows > 0)
    {
    if (!in_array($private_key,$treffer_array))
     {
     echo "$private_key $public_address ";
     echo "Hit ";
     echo "\n";
     array_push($treffer_array,$private_key);
     }
    }
    else
    {
    //echo "$private_key $public_address ";
    //echo "No Hit ";
    //echo "\n";
    }
}
}


?>
