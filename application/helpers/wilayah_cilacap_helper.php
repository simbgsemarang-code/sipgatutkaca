<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists('wilayah_cilacap'))
{
	/**
	 * 24 kecamatan Kabupaten Cilacap beserta desa/kelurahannya, sumber:
	 * https://id.wikipedia.org/wiki/Daftar_kecamatan_dan_kelurahan_di_Kabupaten_Cilacap
	 * Dipakai untuk dropdown Kecamatan -> Desa/Kelurahan bertingkat.
	 */
	function wilayah_cilacap()
	{
		return array(
			'Adipala' => array('Adipala','Adiraja','Adireja Kulon','Adireja Wetan','Bunton','Doplang','Glempangpasir','Gombolharjo','Kalikudi','Karanganyar','Karangbenda','Karangsari','Pedasong','Penggalang','Welahan Wetan','Wlahar'),
			'Bantarsari' => array('Bantarsari','Binangun','Bulaksari','Cikedondong','Citembong','Kamulyan','Kedungwadas','Rawajaya'),
			'Binangun' => array('Alangamba','Bangkal','Binangun','Jati','Jepara Kulon','Jepara Wetan','Karangnangka','Kemojing','Kepudang','Pagubugan','Pagubugan Kulon','Pasuruhan','Pesawahan','Sidaurip','Sidayu','Widarapayung Kulon','Widarapayung Wetan'),
			'Cilacap Selatan' => array('Cilacap','Sidakaya','Tambakreja','Tegalkamulyan','Tegalreja'),
			'Cilacap Tengah' => array('Donan','Gunungsimping','Kutawaru','Lomanis','Sidanegara'),
			'Cilacap Utara' => array('Gumilir','Karangtalun','Kebonmanis','Mertasinga','Tritih Kulon'),
			'Cimanggu' => array('Bantarmangu','Bantarpanjang','Cibalung','Cijati','Cilempuyang','Cimanggu','Cisalak','Karangreja','Karangsari','Kutabima','Mandala','Negarajati','Panimbang','Pesahangan','Rejodadi'),
			'Cipari' => array('Caruy','Cipari','Cisuru','Karangreja','Kutasari','Mekarsari','Mulyadadi','Pegadingan','Segaralangu','Serang','Sidasari'),
			'Dayeuhluhur' => array('Bingkeng','Bolang','Cijeruk','Cilumping','Ciwalen','Datar','Dayeuhluhur','Hanum','Kutaagung','Matenggeng','Panulisan','Panulisan Barat','Panulisan Timur','Sumpinghayu'),
			'Gandrungmangu' => array('Bulusari','Cinangsi','Cisumur','Gandrungmangu','Gandrungmanis','Gintungreja','Karanganyar','Karanggintung','Kertajaya','Layansari','Muktisari','Rungkang','Sidaurip','Wringinharjo'),
			'Jeruklegi' => array('Brebeg','Cilibang','Citepus','Jambusari','Jeruklegi Kulon','Jeruklegi Wetan','Karangkemiri','Mendala','Prapagan','Sawangan','Sumingkir','Tritih Lor','Tritih Wetan'),
			'Kampung Laut' => array('Klaces','Panikel','Ujungalang','Ujunggagak'),
			'Karangpucung' => array('Babakan','Bengbulang','Cidadap','Ciporos','Ciruyung','Gunungtelu','Karangpucung','Pamulihan','Pengawaren','Sidamulya','Sindangbarang','Surusunda','Tayem','Tayemtimur'),
			'Kawunganten' => array('Babakan','Bojong','Bringkeng','Grugu','Kalijeruk','Kawunganten','Kawunganten Lor','Kubangkangkung','Mentasan','Sarwadadi','Sidaurip','Ujungmanik'),
			'Kedungreja' => array('Bangunreja','Bojongsari','Bumireja','Ciklapa','Jatisari','Kaliwungu','Kedungreja','Rejamulya','Sidanegara','Tambakreja','Tambaksari'),
			'Kesugihan' => array('Bulupayung','Ciwuni','Dondong','Jangrana','Kalisabuk','Karangjengkol','Karangkandri','Keleng','Kesugihan','Kesugihan Kidul','Kuripan','Kuripan Kidul','Menganti','Pesanggrahan','Planjan','Slarang'),
			'Kroya' => array('Ayamalas','Bajing','Bajing Kulon','Buntu','Gentasari','Karangmangu','Karangturi','Kedawung','Kroya','Mergawati','Mujur','Mujur Lor','Pekuncen','Pesanggrahan','Pucung Kidul','Pucung Lor','Sikampuh'),
			'Majenang' => array('Bener','Boja','Cibeunying','Cilopadang','Jenang','Mulyadadi','Mulyasari','Padangjaya','Padangsari','Pahonjean','Pengadegan','Sadabumi','Sadahayu','Salebu','Sepatnunggal','Sindangsari','Ujungbarang'),
			'Maos' => array('Glempang','Kalijaran','Karangkemiri','Karangreja','Karangrena','Klapagada','Maos Kidul','Maos Lor','Mernek','Penisihan'),
			'Nusawungu' => array('Banjareja','Banjarsari','Banjarwaru','Danasri','Danasri Kidul','Danasri Lor','Jetis','Karangpakis','Karangputat','Karangsembung','Karangtawang','Kedungbenda','Klumprit','Nusawangkal','Nusawungu','Purwadadi','Sikanco'),
			'Patimuan' => array('Bulupayung','Cimrutu','Cinyawang','Patimuan','Purwodadi','Rawaapu','Sidamukti'),
			'Sampang' => array('Brani','Karangasem','Karangjati','Karangtengah','Ketanggung','Nusajati','Paberasan','Paketingan','Sampang','Sidasari'),
			'Sidareja' => array('Gunungreja','Karanggedang','Kunci','Margasari','Penyarang','Sidamulya','Sidareja','Sudagaran','Tegalsari','Tinggarjaya'),
			'Wanareja' => array('Adimulya','Bantar','Cigintung','Cilongkrang','Jambu','Limbangan','Madura','Madusari','Majingklak','Malabar','Palugon','Purwasari','Sidamulya','Tambaksari','Tarisi','Wanareja'),
		);
	}
}
