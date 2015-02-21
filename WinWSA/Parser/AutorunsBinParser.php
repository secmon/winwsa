<?php
namespace WinWSA\Parser;

/**
 * @file WinWSA\Parser\AutorunsBinParser.php
 * Autoruns binary report parser.
 */

class AutorunsBinParser {
	/** @var string */
	protected $filepath;

	public function __construct($filepath = NULL) {
		if ($filepath) {
			$this->filepath = $filepath;
		}
	}

	public function parse() {

	}

	public function setFilepath($filepath) {
		$this->filepath = $filepath;
	}
}


// function secmon_tmp_parse_arn() {
//   $filepath = '/var/www/secmon/www/sites/all/modules/secmon/arn2.arn';
//   if (!($fp = fopen($filepath, "rb"))) {
//     throw new Exception('Unable to open file', 1);
//   }

//   // Статистика работы функции.
//   $func_stats = array(
//     'items_cnt' => 0,
//     'locs_cnt'  => 0,
//   );

//   // Установка указателя на начало данных.
//   // fseek($fp, 1823636); // arn.arn
//   fseek($fp, 1596820); // arn2.arn

//   // Список вендорских категорий (разделов) в порядке их появления в файле (такой же порядок
//   // в GUI-утилите Autoruns).
//   $vnd_cats = array(
//     "Logon",
//     "Explorer",
//     "Internet Explorer",
//     "Scheduled Tasks",
//     "Services",
//     "Drivers",
//     "Codecs",
//     "Boot Execute",
//     "Image Hijacks",
//     "AppInit",
//     "KnownDLLs",
//     "Winlogon",
//     "Winsock Providers",
//     "Print Monitors",
//     "LSA Providers",
//     "Network Providers",
//     "WMI",
//     "Sidebar Gadgets",
//   );
//   $current_vnt_cat = reset($vnd_cats);
//   dpm('Раздел - '. $current_vnt_cat);

//   // Первой после иконок пойдет location.
//   $separator = '00000000';

//   while (TRUE) {
//     switch ($separator) {
//       // Маркер следующего раздела (вендорской категории). Раздел может быть пустым: в таком 
//       // случае, следующими 4 байтами также будут '0d000000'. Обычно, вне зависимости от 
//       // заполнения разделов, в файле присутствуют маркеры каждого.
//       case '0d000000':
//         $current_vnt_cat = next($vnd_cats);
//         dpm('Раздел - '. $current_vnt_cat);
//         break;

//       // Маркер начала location.
//       case '00000000':
//         $loc = secmon_tmp_parse_arn__read_location($fp);
//         secmon_tmp_parse_arn__dpm($loc);
//         $func_stats['locs_cnt']++;
//         break;

//       // Маркер начала описания показателя.
//       case '2b000000':
//         $item = secmon_tmp_parse_arn__read_item($fp, 'vnd_');
//         $item['vnd_cat'] = $current_vnt_cat;
//         ksort($item);
//         secmon_tmp_parse_arn__dpm($item);
//         $func_stats['items_cnt']++;
//         break;

//       // Разделитель, который стоит либо после показателя, либо после location. Причем, после
//       // последнего показателя/location в файле, он не указывается.
//       case '0a000000':
//         break;

//       // В случае обнаружения неизвестного разделителя, работа прекращается.
//       default:
//         throw new Exception('Unknown separator '. check_plain($separator), 1);
//     }

//     $separator = fread($fp, 4);
//     if (!$separator) {
//       dpm('end');
//       break;
//     }
//     $separator = bin2hex($separator);
//   }

//   dpm($func_stats);

//   fclose($fp);
// }


// function secmon_tmp_parse_arn__dpm($v) {
//   drupal_set_message('<pre>'. print_r($v, 1) . '</pre>');
// }



// function secmon_tmp_parse_arn__read_location($fp) {
//   $loc = array();
//   $loc['location'] = secmon_tmp_parse_arn__read_lendata($fp);
//   $loc['unparsed1'] = bin2hex(secmon_tmp_parse_arn__read_data($fp, 4));
//   $loc['changed'] = secmon_tmp_parse_arn__read_lendata($fp);  
//   return $loc;
// }


// function secmon_tmp_parse_arn__read_item($fp, $key_prefix = '') {
//   $item = array();
//   $item[$key_prefix.'itemname']     = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unparsed1']    = bin2hex(secmon_tmp_parse_arn__read_data($fp, 4));
//   $item[$key_prefix.'description']  = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'signer']       = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'imagepath']    = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'time']         = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unparsed2']    = bin2hex(secmon_tmp_parse_arn__read_data($fp, 20));
//   $item[$key_prefix.'launchstring'] = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'location']     = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unknown1']     = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'size']         = secmon_tmp_parse_arn__read_len($fp);
//   $item[$key_prefix.'time2']        = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'company']      = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unknown2']     = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'version']      = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unknown3']     = secmon_tmp_parse_arn__read_lendata($fp);
//   $item[$key_prefix.'unparsed3']    = bin2hex(secmon_tmp_parse_arn__read_data($fp, 4));
//   $item[$key_prefix.'time3']        = secmon_tmp_parse_arn__read_lendata($fp);
//   return $item;
// }



// function secmon_tmp_parse_arn__read_len($fp) {
//   $length = unpack("i", fread($fp, 4));
//   $length = intval($length[1]);
//   return $length;
// }


// function secmon_tmp_parse_arn__read_lendata($fp) {
//   $length = secmon_tmp_parse_arn__read_len($fp);
//   if (!$length || $length > 800) {
//     return NULL;
//   }
//   return secmon_tmp_parse_arn__read_data($fp, $length);
// }


// function secmon_tmp_parse_arn__read_data($fp, $length) {
//   $data = fread($fp, $length);
//   return $data ? iconv("unicode", "utf-8", $data) : NULL;
// }