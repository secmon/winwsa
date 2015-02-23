<?php

namespace VanaZhilin\WinWSA\Parser;

class AutorunsXML13ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingOfExampleReports() 
    {
    	$parser = new AutorunsXML13Parser();
    	$parser->parse(dirname(__FILE__));
    }
}