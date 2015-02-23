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

    public function testParseAutoruns1301XMLt() {
        $parser = new AutorunsXML13Parser();
        $parser->parse(dirname(__FILE__). '/../../../fixtures/autoruns13.01-xmlt.xml');
    }
}