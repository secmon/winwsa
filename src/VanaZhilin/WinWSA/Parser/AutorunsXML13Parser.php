<?php

namespace VanaZhilin\WinWSA\Parser;

/**
 * Parse Autoruns v13 XML-report. 
 *
 * Tested with versions: 
 * - 13.01
 * 
 * Usage notes:
 * - report should be created using one of these commands:
 *   autorunsc.exe -a * -h -s -x -accepteula -t * > report.xml
 *   autorunsc.exe -a * -h -s -x -accepteula -t -v -vt * > report.xml
 * - normalized UTC timestamps are used (-t flag)
 */
class AutorunsXML13Parser
{
    /** @var array Parsed items */
    protected $items = array();

    /** @var array Currently processing item */
    protected $current_item;

    /** @var string Currently processing XML node name */
    protected $current_node_name;

    /** @var array */
    protected $required_item_keys = array(
        'location',
        'itemname',
        'enabled',
        'profile',
        'launchstring',
        'description',
        'company',
        'signer',
        'version',
        'time',
    );

    /** @var object Pointer to opened report file */
    protected $filepointer;

    /** @var object */
    protected $xml_parser;

    /**
    *  Parse given Autoruns XML report and return assoc array.
    *
     * @param $filepath string Full path of report.
     * @return array
     * 
     * @throws \Exception
     */
    public function parse($filepath) 
    {
        $this->items = array();

        if (!$filepath || !is_file($filepath)) {
            throw new \Exception('Invalid report file path');
        }
        if (!is_readable($filepath) || !($this->filepointer = fopen($filepath, 'r'))) {     
            throw new \Exception('Unable to open report file');
        } 

        $this->xml_parser = xml_parser_create(); 
        xml_set_object($this->xml_parser, $this);
        xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler($this->xml_parser, 'startElementHandler', 'endElementHandler');
        xml_set_character_data_handler($this->xml_parser, 'elementDataHandler');

        while ($data = fgets($this->filepointer)) {   
            if (!xml_parse($this->xml_parser, $data, feof($this->filepointer))) {
                throw new \Exception(sprintf(
                    'XML Error: %d - %s (at line %d)',
                    xml_get_error_code($this->xml_parser),
                    xml_error_string(xml_get_error_code($this->xml_parser)),
                    xml_get_current_line_number($this->xml_parser)
                ));
            } 
        }         

        xml_parser_free($this->xml_parser);
        fclose($this->filepointer);
        $this->filepointer = NULL;
        return $this->items;
    } 

    /**
     * @see xml_set_element_handler()
     */
    protected function startElementHandler($xml_parser, $name, $attrs)
    {
        $this->current_node_name = $name;

        // New item parsing starts.
        if ($name == 'item') {
            $this->current_item = array();
            foreach ($this->required_item_keys as $key) {
                $this->current_item['vnd_'. $key] = '';
            }            
        }
    }

    /**
     * @see xml_set_element_handler()
     */
    protected function endElementHandler($xml_parser, $name)
    {
        switch ($name) {
            // Explode signature field. Examples of value: 
            // (Verified) Microsoft Corporation
            // (Not verified) Microsoft Corporation
            case 'signer':
                foreach (array('verified', 'not verified') as $key) {
                    $br_key = '('. $key .')';
                    if (stripos($this->current_item['vnd_signer'], $br_key) === 0) {
                        $this->current_item['vnd_signer_verified'] = $key;
                        $this->current_item['vnd_signer_name'] = substr($this->current_item['vnd_signer'], strlen($br_key) + 1);
                    }
                }
                if (!isset($this->current_item['vnd_signer_verified'])) {
                    throw new \Exception(sprintf(
                        'Unable to parse signer field value "%s" (at line %d)',
                        $this->current_item['vnd_signer'],
                        xml_get_current_line_number($this->xml_parser)
                    ));
                }
                break;

            // Parse user domain and name from profile field (or mark scope as "computer").
            case 'profile':
                if ($this->current_item['vnd_profile'] == 'System-wide') {
                    $this->current_item['scope'] = 'computer';
                } else {
                    $userdata = explode('\\', $this->current_item['vnd_profile']);
                    if ($userdata && isset($userdata[1])) {
                        $this->current_item['scope'] = 'user';
                        $this->current_item['user_domain'] = strtoupper($userdata[0]);
                        $this->current_item['user_name'] = $userdata[1];                         
                    } else {
                        $this->current_item['scope'] = 'unknown';
                    }
                }
                break;

            // Convert timestamp to full UTC string YYYY-MM-DDTHH:mm:ss.
            case 'time':
                if (preg_match('/^(\d{4})(\d{2})(\d{2})\-(\d{2})(\d{2})(\d{2})$/i', $this->current_item['vnd_time'], $d)) {
                    $this->current_item['vnd_time_utc'] = $d[1].'-'.$d[2].'-'.$d[3].'T'.$d[4].':'.$d[5].':'.$d[6];
                }
                break;
        }

        // Item parsing is finished. Add it to result list.
        if ($name == 'item') {
            $this->current_item = array_map('trim', $this->current_item);
            $this->items[] = $this->current_item;
            $this->current_item = NULL;
        }
    }

    /**
     * @see xml_set_character_data_handler()
     */
    protected function elementDataHandler($xml_parser, $data)
    {
        // Fill item's field (as 'vnd_FIELDNAME'). Handler is called multiple times for each field.
        if (!is_null($this->current_item) && $this->current_node_name != 'item') {
            if (!isset($this->current_item['vnd_'. $this->current_node_name])) {
              $this->current_item['vnd_'. $this->current_node_name] = '';
            }
            $this->current_item['vnd_'. $this->current_node_name] .= $data;
        }
    }

    public function __destruct() 
    {
        if ($this->filepointer) {
            fclose($this->filepointer);
        }
        if (is_resource($this->xml_parser)) {
            xml_parser_free($this->xml_parser);
        }
    }
}