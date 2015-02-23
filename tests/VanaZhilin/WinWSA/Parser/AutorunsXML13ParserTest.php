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

        $this->assertTrue(is_array($items) && isset($items[954]));
        $single_item = $items[954];
        $this->assertEquals(count($single_item), 21);
        $this->assertEquals($single_item['vnd_location'],     'HKCU\Software\Microsoft\Internet Explorer\UrlSearchHooks');
        $this->assertEquals($single_item['vnd_itemname'],     'Microsoft Url Search Hook');
        $this->assertEquals($single_item['vnd_enabled'],      'Enabled');
        $this->assertEquals($single_item['vnd_profile'],      'LOCALHOST\Администратор');
        $this->assertEquals($single_item['vnd_launchstring'], 'HKCR\CLSID\{CFBFAE00-17A6-11D0-99CB-00C04FD64497}');
        $this->assertEquals($single_item['vnd_description'],  'Интернет-обозреватель');
        $this->assertEquals($single_item['vnd_company'],      'Microsoft Corporation');
        $this->assertEquals($single_item['vnd_signer'],       '(Verified) Microsoft Windows');
        $this->assertEquals($single_item['vnd_version'],      '8.0.7600.16385');
        $this->assertEquals($single_item['vnd_time'],         '20090714-010616');
        $this->assertEquals($single_item['scope'],            'user');
        $this->assertEquals($single_item['user_domain'],      'LOCALHOST');
        $this->assertEquals($single_item['user_name'],        'Администратор');
        $this->assertEquals($single_item['vnd_signer_verified'], 'verified');
        $this->assertEquals($single_item['vnd_signer_name'],  'Microsoft Windows');
        $this->assertEquals($single_item['vnd_imagepath'],    'c:\windows\system32\ieframe.dll');
        $this->assertEquals($single_item['vnd_md5hash'],      '672ecbb050f17bf90fe00758596f38ca');
        $this->assertEquals($single_item['vnd_sha1hash'],     '092312ab1ec7dc252795f00e81569994a23356f2');
        $this->assertEquals($single_item['vnd_pesha1hash'],   '692ca91d62b65e562a931bc1437b639658ce65c5290a013b73f051a948ae17f9');
        $this->assertEquals($single_item['vnd_pesha256hash'], 'CF739C5992CAADC5C6730501230B792A8A6C3ED5');
        $this->assertEquals($single_item['vnd_sha256hash'],   'B91A1C93F85AB7E2611AEB097D9B94AE0680E3AB39ACD0D41E16A65DFFDB4733');

        $this->assertEquals(md5(serialize($items)), '0cae1d927cbf8ecaa7e7974a1a04c323');

        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmltlmods.xml');
        $this->assertEquals(md5(serialize($items)), '0cae1d927cbf8ecaa7e7974a1a04c323');
    }

    /**
     * @expectedException \Exception
     */
    public function testParseAutoruns1301XMLtBadSigner() {
        $parser = new AutorunsXML13Parser();
        $items = $parser->parse(dirname(__FILE__) .'/../../../fixtures/autoruns13.01-xmltbadsig.xml');
    }
}