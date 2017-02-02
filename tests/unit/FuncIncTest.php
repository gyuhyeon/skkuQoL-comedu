<?php
require_once( _XE_PATH_.'config/func.inc.php');

class FuncIncTest extends \Codeception\TestCase\Test
{
    static public function provider()
    {
        return array(
            // remove iframe
            array(
                '<div class="frame"><iframe src="path/to/file.html"></iframe><p><a href="#iframe">IFrame</a></p></div>',
                // '<div class="frame">&lt;iframe src="path/to/file.html">&lt;/iframe><p><a href="#iframe">IFrame</a></p></div>'
                '<div class="frame"><iframe></iframe><p><a href="#iframe">IFrame</a></p></div>'
            ),
            // expression
            array(
                '<div class="dummy" style="xss:expr/*XSS*/ession(alert(\'XSS\'))">',
                '<div class="dummy"></div>'
            ),
            // no quotes and no semicolon - http://ha.ckers.org/xss.html
            array(
                '<img src=javascript:alert(\'xss\')>',
                ''
            ),
            // embedded encoded tab to break up XSS - http://ha.ckers.org/xss.html
            array(
                '<IMG SRC="jav&#x09;ascript:alert(\'XSS\');">',
                ''
            ),
            // issue 178
            array(
                '<img src="invalid.jpg"\nonerror="alert(1)" />',
                '<img src="invalid.jpg" alt="invalid.jpg" />'
            ),
            // issue 534
            array(
                '<img src=\'as"df dummy=\'"1234\'" 4321\' asdf/*/>*/"  onerror="console.log(\'Yet another XSS\')">',
                '<img src="as" alt="as&quot;df dummy=" />*/"  onerror="console.log(\'Yet another XSS\')"&gt;'
            ),
            // issue 602
            array(
                '<img alt="test" src="(http://static.naver.com/www/u/2010/0611/nmms_215646753.gif" onload="eval(String.fromCharCode(105,61,49,48,48,59,119,104,105,108,101, 40,105,62,48,41,97,108,101,114,116,40,40,105,45,45,41,43,39,48264,47564,32, 45908,32,53364,47533,54616,49464,50836,39,41,59));">',
                ''
            ),
            // issue #1813 https://github.com/xpressengine/xe-core/issues/1813
            array(
                '<img src="?act=dispLayoutPreview" alt="dummy" />',
                '<img alt="dummy" />'
            ),
            array(
                '<img src="?act =dispLayoutPreview" alt="dummy" />',
                '<img alt="dummy" />'
            ),
            array(
                "<img src=\"?act\n=dispLayoutPreview\" alt=\"dummy\" />",
                '<img alt="dummy" />'
            ),
            array(
                "<img src=\"?pam=act&a\nct  =\r\n\tdispLayoutPreview\" alt=\"dummy\" />",
                '<img alt="dummy" />'
            )
        );
    }

    /**
     * @dataProvider provider
     */
    public function testXss($source, $expected)
    {
        $result = removeHackTag($source);
        $this->assertEquals($result, $expected);
    }
}
