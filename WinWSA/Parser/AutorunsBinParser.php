<?php

namespace WinWSA\Parser;

/**
 * @file WinWSA\Parser\AutorunsBinParser.php
 * Autoruns binary (ARN) report parser.
 */

class AutorunsBinParser 
{
    /**
     * List of vendor categories in order of their appearance in ARN file (same as in Autoruns GUI).
     *
     * @var array
     */
    protected $vnd_cats = array(
        'Logon',
        'Explorer',
        'Internet Explorer',
        'Scheduled Tasks',
        'Services',
        'Drivers',
        'Codecs',
        'Boot Execute',
        'Image Hijacks',
        'AppInit',
        'KnownDLLs',
        'Winlogon',
        'Winsock Providers',
        'Print Monitors',
        'LSA Providers',
        'Network Providers',
        'WMI',
        'Sidebar Gadgets',
    );

    public function __construct() {}

    /**
     * Parse given ARN file.
     *
     * @param $arnpath  string  Path to Autoruns binary report.
     * @param $iconsdir string  Path for saving icons. If NULL, icons will not be extracted.
     * @return array Array of parsed items.
     *
     * @throws \Exception
     */
    public function parse($arnpath, $iconsdir = NULL) 
    {
        if ($iconsdir) {
            if (!is_dir($iconsdir) || !is_writable($iconsdir)) {
                throw new Exception('Path for saving icons in not exists or is not writable', 1);
            }
        }

        if (!($fp = fopen($arnpath, "rb"))) {
            throw new Exception('Unable to open ARN file for reading', 1);
        }



        // Set file pointer to data start.
//   // fseek($fp, 1823636); // arn.arn
//   fseek($fp, 1596820); // arn2.arn

        $current_vnt_cat = reset($this->vnd_cats);

        // location starts first after icons section.
        $separator = '00000000';

        while (TRUE) {
            switch ($separator) {
                // Маркер следующего раздела (вендорской категории). Раздел может быть пустым: в таком 
                // случае, следующими 4 байтами также будут '0d000000'. Обычно, вне зависимости от 
                // заполнения разделов, в файле присутствуют маркеры каждого.
                case '0d000000':
                    $current_vnt_cat = next($vnd_cats);
                    break;

                // Маркер начала location.
                case '00000000':
                    $loc = $this->readLocation($fp);
                    break;

                // Маркер начала описания показателя.
                case '2b000000':
                    $item = $this->readItem($fp, 'vnd_');
                    $item['vnd_cat'] = $current_vnt_cat;
                    ksort($item);
                    break;

                // Разделитель, который стоит либо после показателя, либо после location. Причем, после
                // последнего показателя/location в файле, он не указывается.
                case '0a000000':
                    break;

                // В случае обнаружения неизвестного разделителя, работа прекращается.
                default:
                    throw new Exception('Unknown separator '. htmlspecialchars($separator), 1);
            }

            $separator = fread($fp, 4);
            if (!$separator) {
                break;
            }
            $separator = bin2hex($separator);
        }

        fclose($fp);
    }

    protected function readLocation($fp) 
    {
        $loc = array();
        $loc['location']  = $this->readLendata($fp);
        $loc['unparsed1'] = bin2hex($this->readData($fp, 4));
        $loc['changed']   = $this->readLendata($fp);  
        return $loc;
    }

    protected function readLen($fp) 
    {
        $length = unpack("i", fread($fp, 4));
        $length = intval($length[1]);
        return $length;
    }

    protected function readLendata($fp) 
    {
        $length = $this->readLen($fp);
        if (!$length || $length > 800) {
            return NULL;
        }
        return $this->readData($fp, $length);
    }

    protected function readData($fp, $length) 
    {
        $data = fread($fp, $length);
        return $data ? iconv('unicode', 'utf-8', $data) : NULL;
    }

    protected function readItem($fp, $key_prefix = '') 
    {
        $item = array();
        $item[$key_prefix.'itemname']     = $this->readLendata($fp);
        $item[$key_prefix.'unparsed1']    = bin2hex($this->readData($fp, 4));
        $item[$key_prefix.'description']  = $this->readLendata($fp);
        $item[$key_prefix.'signer']       = $this->readLendata($fp);
        $item[$key_prefix.'imagepath']    = $this->readLendata($fp);
        $item[$key_prefix.'time']         = $this->readLendata($fp);
        $item[$key_prefix.'unparsed2']    = bin2hex($this->readData($fp, 20));
        $item[$key_prefix.'launchstring'] = $this->readLendata($fp);
        $item[$key_prefix.'location']     = $this->readLendata($fp);
        $item[$key_prefix.'unknown1']     = $this->readLendata($fp);
        $item[$key_prefix.'size']         = $this->readLen($fp);
        $item[$key_prefix.'time2']        = $this->readLendata($fp);
        $item[$key_prefix.'company']      = $this->readLendata($fp);
        $item[$key_prefix.'unknown2']     = $this->readLendata($fp);
        $item[$key_prefix.'version']      = $this->readLendata($fp);
        $item[$key_prefix.'unknown3']     = $this->readLendata($fp);
        $item[$key_prefix.'unparsed3']    = bin2hex($this->readData($fp, 4));
        $item[$key_prefix.'time3']        = $this->readLendata($fp);
        return $item;
    }    
}