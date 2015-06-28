<?php
require 'recipe-maker.php';

function struk($pembelian, $bayar, $voucher = 0, $output = null)
{
	$recipe = new RecipeMaker(40);
	$recipe->center('NIMCO STORE')
		   ->breaks();
	$recipe->center('Jl. Cendrawasih No. 25')
		   ->breaks();
	$recipe->center('Demangan Baru Yogyakarta')
		   ->breaks();
	$recipe->center('Telp : ( 0274 ) 549827')
		   ->breaks();
	$recipe->breaks(' ');
	$recipe->left('TRNS-00001')
		   ->right('Nama Kasir')
		   ->breaks();
	$recipe->left(date('d-m-Y'))
		   ->right('Nama Member')
		   ->breaks();
	$recipe->breaks('-');

	$jual        = 0;
	$diskon      = 0;
	$grand_total = 0;
	foreach ($pembelian as $key => $value) {
		$value['sub_total'] = $value['harga'] * $value['qty'];
		$jual += $value['sub_total'];

		$recipe->left($value['kode'] . ' ('. $value['qty'] .' x '. number($value['harga']) .')')
			   ->right(number($value['sub_total']))
			   ->breaks();

		if ($value['diskon']) {
			$recipe->left('Discount')
				   ->right(number($value['diskon']))
				   ->breaks();

			$diskon += $value['diskon'];
		}
	}
	$grand_total = $jual - ($diskon + $voucher);
	$kembali     = $bayar - $grand_total;

	$recipe->breaks('-');
	$recipe->left('Harga Jual')
		   ->right(number($jual))
		   ->breaks();
	$recipe->left('Total Diskon')
		   ->right(number($diskon))
		   ->breaks();
	$recipe->left('Voucher')
		   ->right(number($voucher))
		   ->breaks();
	$recipe->left('Grand Total')
		   ->right(number($grand_total))
		   ->breaks();
	$recipe->left('Bayar')
		   ->right(number($bayar))
		   ->breaks();
	$recipe->left('Kembali')
		   ->right(number($kembali))
		   ->breaks();
	$recipe->breaks(' ');
	$recipe->center('Terima Kasih & Selamat Belanja Kembali')
		   ->breaks();
	$recipe->end();

	if ($output == 'HTML') {
		return $recipe->outputHTML();
	} else {
		return $recipe->output();
	}
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
// show as HTML
$data = struk($pembelian, $bayar, $voucher, $output = 'HTML');

// return data
// $data = struk($pembelian, $bayar, $voucher);

// enable this for print in windows with shared folder only
// $file =  'contoh.txt';  # nama file temporary yang akan dicetak
// $handle = fopen($file, 'w');
// fwrite($handle, $data);
// fclose($handle);


// shell_exec("print /d:\\\\%COMPUTERNAME%\\\\mini-printer contoh.txt");
