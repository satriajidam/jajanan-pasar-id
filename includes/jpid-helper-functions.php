<?php

/**
 * Collection of helper functions for this plugin.
 *
 * All functions declared here are available in the global scope.
 *
 * NOTE:
 * If number of functions listed here are getting bigger, consider
 * moving and grouping them into separate files.
 *
 * @since      1.0.0
 * @package    jajanan-pasar-id
 * @subpackage jajanan-pasar-id/includes
 * @author		 Agastyo Satriaji Idam <play.satriajidam@gmail.com>
 */

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Format an integer into IDR-based currency number.
 *
 * @since    1.0.0
 * @param    float         $price  Price to format.
 * @return   string                The new price format with IDR prefix.
 */
function jpid_to_rupiah( $price ) {
  $decimal_count = strlen( substr( strrchr( $price, '.' ), 1 ) );

  if ( $decimal_count == 0 ) {
    $decimal_count = 2;
  }

  return 'Rp' . number_format( $price, $decimal_count, ',', '.' );
}

/**
 * Get a list of provinces and their cities in Indonesia.
 *
 * @since    1.0.0
 * @return   array    A list of province => city pairs.
 */
function jpid_get_provinces() {
  return array(
    // Sumatera
    'Aceh' => array(
      'Kabupaten Aceh Barat',
      'Kabupaten Aceh Barat Daya',
      'Kabupaten Aceh Besar',
      'Kabupaten Aceh Jaya',
      'Kabupaten Aceh Selatan',
      'Kabupaten Aceh Singkil',
      'Kabupaten Aceh Tamiang',
      'Kabupaten Aceh Tengah',
      'Kabupaten Aceh Tenggara',
      'Kabupaten Aceh Timur',
      'Kabupaten Aceh Utara',
      'Kabupaten Bener Meriah',
      'Kabupaten Bireuen',
      'Kabupaten Gayo Lues',
      'Kabupaten Nagan Raya',
      'Kabupaten Pidie',
      'Kabupaten Pidie Jaya',
      'Kabupaten Simeulue',
      'Kota Banda Aceh',
      'Kota Langsa',
      'Kota Lhokseumawe',
      'Kota Sabang',
      'Kota Subulussalam'
    ),
    'Sumatera Utara' => array(
      'Kabupaten Asahan',
      'Kabupaten Batubara',
      'Kabupaten Dairi',
      'Kabupaten Deli Serdang',
      'Kabupaten Humbang Hasundutan',
      'Kabupaten Karo',
      'Kabupaten Labuhanbatu',
      'Kabupaten Labuhanbatu Selatan',
      'Kabupaten Labuhanbatu Utara',
      'Kabupaten Langkat',
      'Kabupaten Mandailing Natal',
      'Kabupaten Nias',
      'Kabupaten Nias Barat',
      'Kabupaten Nias Selatan',
      'Kabupaten Nias Utara',
      'Kabupaten Padang Lawas',
      'Kabupaten Padang Lawas Utara',
      'Kabupaten Pakpak Bharat',
      'Kabupaten Samosir',
      'Kabupaten Serdang Bedagai',
      'Kabupaten Simalungun',
      'Kabupaten Tapanuli Selatan',
      'Kabupaten Tapanuli Tengah',
      'Kabupaten Tapanuli Utara',
      'Kabupaten Toba Samosir',
      'Kota Binjai',
      'Kota Gunungsitoli',
      'Kota Medan',
      'Kota Padangsidempuan',
      'Kota Pematangsiantar',
      'Kota Sibolga',
      'Kota Tanjungbalai',
      'Kota Tebing Tinggi'
    ),
    'Sumatera Barat' => array(
      'Kabupaten Agam',
      'Kabupaten Dharmasraya',
      'Kabupaten Kepulauan Mentawai',
      'Kabupaten Lima Puluh Kota',
      'Kabupaten Padang Pariaman',
      'Kabupaten Pasaman',
      'Kabupaten Pasaman Barat',
      'Kabupaten Pesisir Selatan',
      'Kabupaten Sijunjung',
      'Kabupaten Solok',
      'Kabupaten Solok Selatan',
      'Kabupaten Tanah Datar',
      'Kota Bukittinggi',
      'Kota Padang',
      'Kota Padangpanjang',
      'Kota Pariaman',
      'Kota Payakumbuh',
      'Kota Sawahlunto',
      'Kota Solok'
    ),
    'Riau' => array(
      'Kabupaten Bengkalis',
      'Kabupaten Indragiri Hilir',
      'Kabupaten Indragiri Hulu',
      'Kabupaten Kampar',
      'Kabupaten Kepulauan Meranti',
      'Kabupaten Kuantan Singingi',
      'Kabupaten Pelalawan',
      'Kabupaten Rokan Hilir',
      'Kabupaten Rokan Hulu',
      'Kabupaten Siak',
      'Kota Dumai',
      'Kota Pekanbaru'
    ),
    'Kepulauan Riau' => array(
      'Kabupaten Bintan',
      'Kabupaten Karimun',
      'Kabupaten Kepulauan Anambas',
      'Kabupaten Lingga',
      'Kabupaten Natuna',
      'Kota Batam',
      'Kota Tanjung Pinang'
    ),
    'Jambi' => array(
      'Kabupaten Batanghari',
      'Kabupaten Bungo',
      'Kabupaten Kerinci',
      'Kabupaten Merangin',
      'Kabupaten Muaro Jambi',
      'Kabupaten Sarolangun',
      'Kabupaten Tanjung Jabung Barat',
      'Kabupaten Tanjung Jabung Timur',
      'Kabupaten Tebo',
      'Kota Jambi',
      'Kota Sungai Penuh'
    ),
    'Sumatera Selatan' => array(
      'Kabupaten Banyuasin',
      'Kabupaten Empat Lawang',
      'Kabupaten Lahat',
      'Kabupaten Muara Enim',
      'Kabupaten Musi Banyuasin',
      'Kabupaten Musi Rawas',
      'Kabupaten Musi Rawas Utara',
      'Kabupaten Ogan Ilir',
      'Kabupaten Ogan Komering Ilir',
      'Kabupaten Ogan Komering Ulu',
      'Kabupaten Ogan Komering Ulu Selatan',
      'Kabupaten Ogan Komering Ulu Timur',
      'Kabupaten Penukal Abab Lematang Ilir',
      'Kota Lubuklinggau',
      'Kota Pagar Alam',
      'Kota Palembang',
      'Kota Prabumulih'
    ),
    'Bangka Belitung' => array(
      'Kabupaten Bangka',
      'Kabupaten Bangka Barat',
      'Kabupaten Bangka Selatan',
      'Kabupaten Bangka Tengah',
      'Kabupaten Belitung',
      'Kabupaten Belitung Timur',
      'Kota Pangkal Pinang'
    ),
    'Bengkulu' => array(
      'Kabupaten Bengkulu Selatan',
      'Kabupaten Bengkulu Tengah',
      'Kabupaten Bengkulu Utara',
      'Kabupaten Kaur',
      'Kabupaten Kepahiang',
      'Kabupaten Lebong',
      'Kabupaten Mukomuko',
      'Kabupaten Rejang Lebong',
      'Kabupaten Seluma',
      'Kota Bengkulu'
    ),
    'Lampung' => array(
      'Kabupaten Lampung Barat',
      'Kabupaten Lampung Selatan',
      'Kabupaten Lampung Tengah',
      'Kabupaten Lampung Timur',
      'Kabupaten Lampung Utara',
      'Kabupaten Mesuji',
      'Kabupaten Pesawaran',
      'Kabupaten Pesisir Barat',
      'Kabupaten Pringsewu',
      'Kabupaten Tanggamus',
      'Kabupaten Tulang Bawang',
      'Kabupaten Tulang Bawang Barat',
      'Kabupaten Way Kanan',
      'Kota Bandar Lampung',
      'Kota Metro'
    ),

    // Jawa
    'DKI Jakarta' => array(
      'Kabupaten Kepulauan Seribu',
      'Kota Jakarta Barat',
      'Kota Jakarta Pusat',
      'Kota Jakarta Selatan',
      'Kota Jakarta Timur',
      'Kota Jakarta Utara'
    ),
    'Jawa Barat' => array(
      'Kabupaten Bandung',
      'Kabupaten Bandung Barat',
      'Kabupaten Bekasi',
      'Kabupaten Bogor',
      'Kabupaten Ciamis',
      'Kabupaten Cianjur',
      'Kabupaten Cirebon',
      'Kabupaten Garut',
      'Kabupaten Indramayu',
      'Kabupaten Karawang',
      'Kabupaten Kuningan',
      'Kabupaten Majalengka',
      'Kabupaten Pangandaran',
      'Kabupaten Purwakarta',
      'Kabupaten Subang',
      'Kabupaten Sukabumi',
      'Kabupaten Sumedang',
      'Kabupaten Tasikmalaya',
      'Kota Bandung',
      'Kota Banjar',
      'Kota Bekasi',
      'Kota Bogor',
      'Kota Cimahi',
      'Kota Cirebon',
      'Kota Depok',
      'Kota Sukabumi',
      'Kota Tasikmalaya'
    ),
    'Banten' => array(
      'Kabupaten Lebak',
      'Kabupaten Pandeglang',
      'Kabupaten Serang',
      'Kabupaten Tangerang',
      'Kota Cilegon',
      'Kota Serang',
      'Kota Tangerang',
      'Kota Tangerang Selatan'
    ),
    'Jawa Tengah' => array(
      'Kabupaten Banjarnegara',
      'Kabupaten Banyumas',
      'Kabupaten Batang',
      'Kabupaten Blora',
      'Kabupaten Boyolali',
      'Kabupaten Brebes',
      'Kabupaten Cilacap',
      'Kabupaten Demak',
      'Kabupaten Grobogan',
      'Kabupaten Jepara',
      'Kabupaten Karanganyar',
      'Kabupaten Kebumen',
      'Kabupaten Kendal',
      'Kabupaten Klaten',
      'Kabupaten Kudus',
      'Kabupaten Magelang',
      'Kabupaten Pati',
      'Kabupaten Pekalongan',
      'Kabupaten Pemalang',
      'Kabupaten Purbalingga',
      'Kabupaten Purworejo',
      'Kabupaten Rembang',
      'Kabupaten Semarang',
      'Kabupaten Sragen',
      'Kabupaten Sukoharjo',
      'Kabupaten Tegal',
      'Kabupaten Temanggung',
      'Kabupaten Wonogiri',
      'Kabupaten Wonosobo',
      'Kota Magelang',
      'Kota Pekalongan',
      'Kota Salatiga',
      'Kota Semarang',
      'Kota Surakarta',
      'Kota Tegal'
    ),
    'DI Yogyakarta' => array(
      'Kabupaten Bantul',
      'Kabupaten Gunungkidul',
      'Kabupaten Kulon Progo',
      'Kabupaten Sleman',
      'Kota Yogyakarta'
    ),
    'Jawa Timur' => array(
      'Kabupaten Bangkalan',
      'Kabupaten Banyuwangi',
      'Kabupaten Blitar',
      'Kabupaten Bojonegoro',
      'Kabupaten Bondowoso',
      'Kabupaten Gresik',
      'Kabupaten Jember',
      'Kabupaten Jombang',
      'Kabupaten Kediri',
      'Kabupaten Lamongan',
      'Kabupaten Lumajang',
      'Kabupaten Madiun',
      'Kabupaten Magetan',
      'Kabupaten Malang',
      'Kabupaten Mojokerto',
      'Kabupaten Nganjuk',
      'Kabupaten Ngawi',
      'Kabupaten Pacitan',
      'Kabupaten Pamekasan',
      'Kabupaten Pasuruan',
      'Kabupaten Ponorogo',
      'Kabupaten Probolinggo',
      'Kabupaten Sampang',
      'Kabupaten Sidoarjo',
      'Kabupaten Situbondo',
      'Kabupaten Sumenep',
      'Kabupaten Trenggalek',
      'Kabupaten Tuban',
      'Kabupaten Tulungagung',
      'Kota Batu',
      'Kota Blitar',
      'Kota Kediri',
      'Kota Madiun',
      'Kota Malang',
      'Kota Mojokerto',
      'Kota Pasuruan',
      'Kota Probolinggo',
      'Kota Surabaya'
    ),

    // Bali & Nusa Tenggara
    'Bali' => array(
      'Kabupaten Badung',
      'Kabupaten Bangli',
      'Kabupaten Buleleng',
      'Kabupaten Gianyar',
      'Kabupaten Jembrana',
      'Kabupaten Karangasem',
      'Kabupaten Klungkung',
      'Kabupaten Tabanan',
      'Kota Denpasar'
    ),
    'Nusa Tenggara Barat' => array(
      'Kabupaten Bima',
      'Kabupaten Dompu',
      'Kabupaten Lombok Barat',
      'Kabupaten Lombok Tengah',
      'Kabupaten Lombok Timur',
      'Kabupaten Lombok Utara',
      'Kabupaten Sumbawa',
      'Kabupaten Sumbawa Barat',
      'Kota Bima',
      'Kota Mataram'
    ),
    'Nusa Tenggara Timur' => array(
      'Kabupaten Alor',
      'Kabupaten Belu',
      'Kabupaten Ende',
      'Kabupaten Flores Timur',
      'Kabupaten Kupang',
      'Kabupaten Lembata',
      'Kabupaten Malaka',
      'Kabupaten Manggarai',
      'Kabupaten Manggarai Barat',
      'Kabupaten Manggarai Timur',
      'Kabupaten Ngada',
      'Kabupaten Nagekeo',
      'Kabupaten Rote Ndao',
      'Kabupaten Sabu Raijua',
      'Kabupaten Sikka',
      'Kabupaten Sumba Barat',
      'Kabupaten Sumba Barat Daya',
      'Kabupaten Sumba Tengah',
      'Kabupaten Sumba Timur',
      'Kabupaten Timor Tengah Selatan',
      'Kabupaten Timor Tengah Utara',
      'Kota Kupang'
    ),

    // Kalimantan
    'Kalimantan Barat' => array(
      'Kabupaten Bengkayang',
      'Kabupaten Kapuas Hulu',
      'Kabupaten Kayong Utara',
      'Kabupaten Ketapang',
      'Kabupaten Kubu Raya',
      'Kabupaten Landak',
      'Kabupaten Melawi',
      'Kabupaten Mempawah',
      'Kabupaten Sambas',
      'Kabupaten Sanggau',
      'Kabupaten Sekadau',
      'Kabupaten Sintang',
      'Kota Pontianak',
      'Kota Singkawang'
    ),
    'Kalimantan Tengah' => array(
      'Kabupaten Barito Selatan',
      'Kabupaten Barito Timur',
      'Kabupaten Barito Utara',
      'Kabupaten Gunung Mas',
      'Kabupaten Kapuas',
      'Kabupaten Katingan',
      'Kabupaten Kotawaringin Barat',
      'Kabupaten Kotawaringin Timur',
      'Kabupaten Lamandau',
      'Kabupaten Murung Raya',
      'Kabupaten Pulang Pisau',
      'Kabupaten Sukamara',
      'Kabupaten Seruyan',
      'Kota Palangka Raya'
    ),
    'Kalimantan Selatan' => array(
      'Kabupaten Balangan',
      'Kabupaten Banjar',
      'Kabupaten Barito Kuala',
      'Kabupaten Hulu Sungai Selatan',
      'Kabupaten Hulu Sungai Tengah',
      'Kabupaten Hulu Sungai Utara',
      'Kabupaten Kotabaru',
      'Kabupaten Tabalong',
      'Kabupaten Tanah Bumbu',
      'Kabupaten Tanah Laut',
      'Kabupaten Tapin',
      'Kota Banjarbaru',
      'Kota Banjarmasin'
    ),
    'Kalimantan Timur' => array(
      'Kabupaten Berau',
      'Kabupaten Kutai Barat',
      'Kabupaten Kutai Kartanegara',
      'Kabupaten Kutai Timur',
      'Kabupaten Mahakam Ulu',
      'Kabupaten Paser',
      'Kabupaten Penajam Paser Utara',
      'Kota Balikpapan',
      'Kota Bontang',
      'Kota Samarinda'
    ),
    'Kalimantan Utara' => array(
      'Kabupaten Bulungan',
      'Kabupaten Malinau',
      'Kabupaten Nunukan',
      'Kabupaten Tana Tidung',
      'Kota Tarakan'
    ),

    // Sulawesi
    'Sulawesi Utara' => array(
      'Kabupaten Bolaang Mongondow',
      'Kabupaten Bolaang Mongondow Selatan',
      'Kabupaten Bolaang Mongondow Timur',
      'Kabupaten Bolaang Mongondow Utara',
      'Kabupaten Kepulauan Sangihe',
      'Kabupaten Kepulauan Siau Tagulandang Biaro',
      'Kabupaten Kepulauan Talaud',
      'Kabupaten Minahasa',
      'Kabupaten Minahasa Selatan',
      'Kabupaten Minahasa Tenggara',
      'Kabupaten Minahasa Utara',
      'Kota Bitung',
      'Kota Kotamobagu',
      'Kota Manado',
      'Kota Tomohon'
    ),
    'Sulawesi Barat' => array(
      'Kabupaten Majene',
      'Kabupaten Mamasa',
      'Kabupaten Mamuju',
      'Kabupaten Mamuju Tengah',
      'Kabupaten Mamuju Utara',
      'Kabupaten Polewali Mandar'
    ),
    'Sulawesi Tengah' => array(
      'Kabupaten Banggai',
      'Kabupaten Banggai Kepulauan',
      'Kabupaten Banggai Laut',
      'Kabupaten Buol',
      'Kabupaten Donggala',
      'Kabupaten Morowali',
      'Kabupaten Morowali Utara',
      'Kabupaten Parigi Moutong',
      'Kabupaten Poso',
      'Kabupaten Sigi',
      'Kabupaten Tojo Una-Una',
      'Kabupaten Tolitoli',
      'Kota Palu'
    ),
    'Sulawesi Tenggara' => array(
      'Kabupaten Bombana',
      'Kabupaten Buton',
      'Kabupaten Buton Selatan',
      'Kabupaten Buton Tengah',
      'Kabupaten Buton Utara',
      'Kabupaten Kolaka',
      'Kabupaten Kolaka Timur',
      'Kabupaten Kolaka Utara',
      'Kabupaten Konawe',
      'Kabupaten Konawe Kepulauan',
      'Kabupaten Konawe Selatan',
      'Kabupaten Konawe Utara',
      'Kabupaten Muna',
      'Kabupaten Muna Barat',
      'Kabupaten Wakatobi',
      'Kota Bau-Bau',
      'Kota Kendari'
    ),
    'Sulawesi Selatan' => array(
      'Kabupaten Bantaeng',
      'Kabupaten Barru',
      'Kabupaten Bone',
      'Kabupaten Bulukumba',
      'Kabupaten Enrekang',
      'Kabupaten Gowa',
      'Kabupaten Jeneponto',
      'Kabupaten Kepulauan Selayar',
      'Kabupaten Luwu',
      'Kabupaten Luwu Timur',
      'Kabupaten Luwu Utara',
      'Kabupaten Maros',
      'Kabupaten Pangkajene dan Kepulauan',
      'Kabupaten Pinrang',
      'Kabupaten Sidenreng Rappang',
      'Kabupaten Sinjai',
      'Kabupaten Soppeng',
      'Kabupaten Takalar',
      'Kabupaten Tana Toraja',
      'Kabupaten Toraja Utara',
      'Kabupaten Wajo',
      'Kota Makassar',
      'Kota Palopo',
      'Kota Parepare'
    ),
    'Gorontalo' => array(
      'Kabupaten Boalemo',
      'Kabupaten Bone Bolango',
      'Kabupaten Gorontalo',
      'Kabupaten Gorontalo Utara',
      'Kabupaten Pohuwato',
      'Kota Gorontalo'
    ),

    // Maluku & Papua
    'Maluku' => array(
      'Kabupaten Buru',
      'Kabupaten Buru Selatan',
      'Kabupaten Kepulauan Aru',
      'Kabupaten Maluku Barat Daya',
      'Kabupaten Maluku Tengah',
      'Kabupaten Maluku Tenggara',
      'Kabupaten Maluku Tenggara Barat',
      'Kabupaten Seram Bagian Barat',
      'Kabupaten Seram Bagian Timur',
      'Kota Ambon',
      'Kota Tual'
    ),
    'Maluku Utara' => array(
      'Kabupaten Halmahera Barat',
      'Kabupaten Halmahera Tengah',
      'Kabupaten Halmahera Timur',
      'Kabupaten Halmahera Selatan',
      'Kabupaten Halmahera Utara',
      'Kabupaten Kepulauan Sula',
      'Kabupaten Pulau Morotai',
      'Kabupaten Pulau Taliabu',
      'Kota Ternate',
      'Kota Tidore Kepulauan'
    ),
    'Papua' => array(
      'Kabupaten Asmat',
      'Kabupaten Biak Numfor',
      'Kabupaten Boven Digoel',
      'Kabupaten Deiyai',
      'Kabupaten Dogiyai',
      'Kabupaten Intan Jaya',
      'Kabupaten Jayapura',
      'Kabupaten Jayawijaya',
      'Kabupaten Keerom',
      'Kabupaten Kepulauan Yapen',
      'Kabupaten Lanny Jaya',
      'Kabupaten Mamberamo Raya',
      'Kabupaten Mamberamo Tengah',
      'Kabupaten Mappi',
      'Kabupaten Merauke',
      'Kabupaten Mimika',
      'Kabupaten Nabire',
      'Kabupaten Nduga',
      'Kabupaten Paniai',
      'Kabupaten Pegunungan Bintang',
      'Kabupaten Puncak',
      'Kabupaten Puncak Jaya',
      'Kabupaten Sarmi',
      'Kabupaten Supiori',
      'Kabupaten Tolikara',
      'Kabupaten Waropen',
      'Kabupaten Yahukimo',
      'Kabupaten Yalimo',
      'Kota Jayapura'
    ),
    'Papua Barat' => array(
      'Kabupaten Fakfak',
      'Kabupaten Kaimana',
      'Kabupaten Manokwari',
      'Kabupaten Manokwari Selatan',
      'Kabupaten Maybrat',
      'Kabupaten Pegunungan Arfak',
      'Kabupaten Raja Ampat',
      'Kabupaten Sorong',
      'Kabupaten Sorong Selatan',
      'Kabupaten Tambrauw',
      'Kabupaten Teluk Bintuni',
      'Kabupaten Teluk Wondama',
      'Kota Sorong'
    )
  );
}

/**
 * Get a list of provinces in Indonesia.
 *
 * @since    1.0.0
 * @return   array    A list of available provinces.
 */
function jpid_get_province_list() {
  return array_keys( jpid_get_provinces() );
}
