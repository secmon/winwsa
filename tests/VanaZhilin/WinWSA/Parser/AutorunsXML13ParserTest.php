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
        $parser->parse('unexistedfile210573');
    }

    public function testParseAutoruns1301XMLt() {
        $parser = new AutorunsXML13Parser();
        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmlt.xml');
        $this->assertEquals('0cae1d927cbf8ecaa7e7974a1a04c323', md5(serialize($items)));

        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmltlmods.xml');
        $this->assertEquals('0cae1d927cbf8ecaa7e7974a1a04c323', md5(serialize($items)));
    }
}