<?php

namespace WinWSA\Parser;

class AutorunsXMLParserFactory
{
    /**
     * @throws \Exception
     */
    public static function getParser($version) 
    {
        switch ($version) {
            case '13':
                return new \WinWSA\Parser\AutorunsXML13Parser();
            default:
                throw new Exception('Unknown Autoruns XML Parser version', 1);
        }
    } 
}