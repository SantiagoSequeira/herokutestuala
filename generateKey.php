<?php
if(isset($_POST["cert"])) {
	$toSign = realpath('mensaje.txt');
	$signed = realpath('firmado.txt');
	$cert = realpath('CertificadoDemo.pem');
	$key = realpath('PrivateKeyDemo.key');

	$pSign = $_POST["toSign"];
	$pCert = '-----BEGIN CERTIFICATE-----' ."\n" . str_replace(' ',"\n", $_POST["cert"]) . "\n" . '-----END CERTIFICATE-----';
	$pKey = '-----BEGIN RSA PRIVATE KEY-----'. "\n" . str_replace(' ',"\n", $_POST["key"]) ."\n" .'-----END RSA PRIVATE KEY-----';

	$fp = fopen($toSign, "w");
	fputs($fp, $pSign);
	fclose($fp);

	$fp = fopen($cert, "w");
	fputs($fp, $pCert);
	fclose($fp);

	$fp = fopen($key, "w");
	fputs($fp, $pKey);
	fclose($fp);

	$prepend = "file://";
	if(openssl_pkcs7_sign($toSign, $signed,
	        $prepend . $cert,
	        array($prepend . $key, ""),
	        array(),
	        !PKCS7_DETACHED)) {
		$signedFile = fopen($signed, "r");
		print(fread($signedFile, filesize("firmado.txt")));
		fclose($signedFile);

		$fp = fopen($toSign, "w");
		fputs($fp, '');
		fclose($fp);

		$fp = fopen($cert, "w");
		fputs($fp, '');
		fclose($fp);

		$fp = fopen($key, "w");
		fputs($fp, '');
		fclose($fp);
	}

	
} else {
	exit("ERROR");
}

?>