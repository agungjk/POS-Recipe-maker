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

	$jual         = 0;
	$total_diskon = 0;
	$grand_total  = 0;
	foreach ($pembelian as $key => $value) {
		$value['sub_total'] = $value['harga'] * $value['qty'];
		$jual += $value['sub_total'];

		$recipe->left($value['kode'] . ' ('. $value['qty'] .' x '. number($value['harga']) .')')
			   ->right(number($value['sub_total']))
			   ->sparator()
			   ->breaks();

		if ($value['diskon']) {
			$diskon = $value['diskon'] * $value['sub_total'] / 100;
			$recipe->left('- Diskon (' . $value['diskon'] . '%)')
				   ->right(number($diskon))
				   ->sparator()
				   ->breaks();

			$total_diskon += $diskon;
		}
	}
	$grand_total = $jual - ($total_diskon + $voucher);
	$kembali     = $bayar - $grand_total;

	$recipe->breaks('-');
	$recipe->left('Harga Jual')
		   ->right(number($jual))
		   ->sparator()
		   ->breaks();
	$recipe->left('Total Diskon')
		   ->right(number($total_diskon))
		   ->sparator()
		   ->breaks();
	$recipe->left('Voucher')
		   ->right(number($voucher))
		   ->sparator()
		   ->breaks();
	$recipe->left('Grand Total')
		   ->right(number($grand_total))
		   ->sparator()
		   ->breaks();
	$recipe->left('Bayar')
		   ->right(number($bayar))
		   ->sparator()
		   ->breaks();
	$recipe->left('Kembali')
		   ->right(number($kembali))
		   ->sparator()
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
			'diskon' => '20'
		],
		[
			'kode' => 'NMC-LSH-002-S',
			'qty' => '1',
			'harga' => '125000',
			'diskon' => '20'
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
