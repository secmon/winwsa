<?php

namespace VanaZhilin\WinWSA\Parser;

class AutorunsXML13ParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException \Exception
	 */
    public function testParseUnexistentFile() 
    {
    	$parser = new AutorunsXML13Parser();
    	$parser->parse('unexistredfile210573');
    }
}