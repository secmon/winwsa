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
        $single_item = $items[954];

        $this->assertEquals(21, count($single_item));
        $this->assertEquals('HKCU\Software\Microsoft\Internet Explorer\UrlSearchHooks', $single_item['vnd_location']);
        $this->assertEquals('Microsoft Url Search Hook', $single_item['vnd_itemname']);
        $this->assertEquals('Enabled', $single_item['vnd_enabled']);
        $this->assertEquals('LOCALHOST\Администратор', $single_item['vnd_profile']);
        $this->assertEquals('HKCR\CLSID\{CFBFAE00-17A6-11D0-99CB-00C04FD64497}', $single_item['vnd_launchstring']);
        $this->assertEquals('Интернет-обозреватель', $single_item['vnd_description']);
        $this->assertEquals('Microsoft Corporation', $single_item['vnd_company']);
        $this->assertEquals('(Verified) Microsoft Windows', $single_item['vnd_signer']);
        $this->assertEquals('8.0.7600.16385', $single_item['vnd_version']);
        $this->assertEquals('20090714-010616', $single_item['vnd_time']);
        $this->assertEquals('user', $single_item['scope']);
        $this->assertEquals('LOCALHOST', $single_item['user_domain']);
        $this->assertEquals('Администратор', $single_item['user_name']);
        $this->assertEquals('verified', $single_item['vnd_signer_verified']);
        $this->assertEquals('Microsoft Windows', $single_item['vnd_signer_name']);
        $this->assertEquals('c:\windows\system32\ieframe.dll', $single_item['vnd_imagepath']);
        $this->assertEquals('672ecbb050f17bf90fe00758596f38ca', $single_item['vnd_md5hash']);
        $this->assertEquals('092312ab1ec7dc252795f00e81569994a23356f2', $single_item['vnd_sha1hash']);
        $this->assertEquals('692ca91d62b65e562a931bc1437b639658ce65c5290a013b73f051a948ae17f9', $single_item['vnd_pesha1hash']);
        $this->assertEquals('CF739C5992CAADC5C6730501230B792A8A6C3ED5', $single_item['vnd_pesha256hash']);
        $this->assertEquals('B91A1C93F85AB7E2611AEB097D9B94AE0680E3AB39ACD0D41E16A65DFFDB4733', $single_item['vnd_sha256hash']);
        $this->assertEquals('0cae1d927cbf8ecaa7e7974a1a04c323', md5(serialize($items)));

        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmltlmods.xml');
        $this->assertEquals('0cae1d927cbf8ecaa7e7974a1a04c323', md5(serialize($items)));
    }

    /**
     * @expectedException \Exception
     */
    public function testParseAutoruns1301XMLtBadSigner() {
        $parser = new AutorunsXML13Parser();
        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmltbadsig.xml');
    }
}