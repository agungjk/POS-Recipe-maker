<?php
ini_set('display_errors', 1);
ini_set('html_errors', 0);
error_reporting(-1);

require 'recipe-maker.php';

function struk($pembelian, $bayar, $voucher = 0)
{
	$recipe = new RecipeMaker(40);
	$recipe->center('NIMCO STORE');
	$recipe->breaks();
	$recipe->center('Jl. Cendrawasih No. 25');
	$recipe->breaks();
	$recipe->center('Demangan Baru Yogyakarta');
	$recipe->breaks();
	$recipe->center('Telp : ( 0274 ) 549827');
	$recipe->breaks();
	$recipe->breaks(' ');
	$recipe->left('TRNS-00001');
	$recipe->right('Nama Kasir');
	$recipe->breaks();
	$recipe->left(date('d-m-Y'));
	$recipe->right('Nama Member');
	$recipe->breaks();
	$recipe->breaks('-');

	$jual        = 0;
	$diskon      = 0;
	$grand_total = 0;
	foreach ($pembelian as $key => $value) {
		$value['sub_total'] = $value['harga'] * $value['qty'];
		$jual += $value['sub_total'];

		$recipe->left($value['kode'] . ' ('. $value['qty'] .' x '. number($value['harga']) .')');
		$recipe->right(number($value['sub_total']));
		$recipe->breaks();

		if ($value['diskon']) {
			$recipe->left('Discount');
			$recipe->right(number($value['diskon']));
			$recipe->breaks();

			$diskon += $value['diskon'];
		}
	}
	$grand_total = $jual - ($diskon + $voucher);
	$kembali = $bayar - $grand_total;

	$recipe->breaks('-');
	$recipe->left('Harga Jual');
	$recipe->right(number($jual));
	$recipe->breaks();
	$recipe->left('Total Diskon');
	$recipe->right(number($diskon));
	$recipe->breaks();
	$recipe->left('Voucher');
	$recipe->right(number($voucher));
	$recipe->breaks();
	$recipe->left('Grand Total');
	$recipe->right(number($grand_total));
	$recipe->breaks();
	$recipe->left('Bayar');
	$recipe->right(number($bayar));
	$recipe->breaks();
	$recipe->left('Kembali');
	$recipe->right(number($kembali));
	$recipe->breaks();
	$recipe->breaks(' ');
	$recipe->center('Terima Kasih & Selamat Belanja Kembali');
	$recipe->breaks();
	$recipe->end();
	return $recipe->output();
}

function number($value)
{
	return number_format($value, '0', ',', '.');
}

$pembelian = [
		[
			'kode' => 'NMC-LSH-001-S',
			'qty' => '1',
			'harga' => '125000',
			'diskon' => '25000'
		],
		[
			'kode' => 'NMC-LSH-002-S',
			'qty' => '1',
			'harga' => '125000',
			'diskon' => '25000'
		]
	];

$bayar = 200000;
$voucher = 10000;
$data = struk($pembelian, $bayar, $voucher);

$file =  'contoh.txt';  # nama file temporary yang akan dicetak
$handle = fopen($file, 'w');
fwrite($handle, $data);
fclose($handle);


shell_exec("print /d:\\\\%COMPUTERNAME%\\\\mini-printer contoh.txt");
