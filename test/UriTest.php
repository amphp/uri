<?php

namespace Amp\Uri;

use PHPUnit\Framework\TestCase;

class UriTest extends TestCase {
    public function provideResolvables() {
        return [
            ['http://localhost/1/2/a.php', 'http://google.com/', 'http://google.com/'],
            [
                'http://www.google.com/',
                '/level1/level2/test.php',
                'http://www.google.com/level1/level2/test.php',
            ],
            ['http://localhost/1/2/a.php', '../b.php', 'http://localhost/1/b.php'],
            ['http://localhost/1/2/a.php', '../../b.php', 'http://localhost/b.php'],
            ['http://localhost/', './', 'http://localhost/'],
            ['http://localhost/', './dir/', 'http://localhost/dir/'],
            ['http://localhost/', '././', 'http://localhost/'],
            ['http://localhost/', '././dir/', 'http://localhost/dir/'],
            ['http://localhost/', '#frag', 'http://localhost/#frag'],
            ['http://localhost/', '?query', 'http://localhost/?query'],
            [
                'http://localhost/',
                'http://www.google.com/%22-%3Eresolve%28%22..%2F..%2F%22%29',
                'http://www.google.com/%22-%3Eresolve%28%22..%2F..%2F%22%29',
            ],
            ["http://a/b/c/d;p?q", "g", "http://a/b/c/g"],
            ["http://a/b/c/d;p?q", "./g", "http://a/b/c/g"],
            ["http://a/b/c/d;p?q", "g/", "http://a/b/c/g/"],
            ["http://a/b/c/d;p?q", "/g", "http://a/g"],
            ["http://a/b/c/d;p?q", "//g", "http://g"],
            ["http://a/b/c/d;p?q", "?y", "http://a/b/c/d;p?y"],
            ["http://a/b/c/d;p?q", "g?y", "http://a/b/c/g?y"],
            ["http://a/b/c/d;p?q", "#s", "http://a/b/c/d;p?q#s"],
            ["http://a/b/c/d;p?q", "g#s", "http://a/b/c/g#s"],
            ["http://a/b/c/d;p?q", "g?y#s", "http://a/b/c/g?y#s"],
            ["http://a/b/c/d;p?q", ";x", "http://a/b/c/;x"],
            ["http://a/b/c/d;p?q", "g;x", "http://a/b/c/g;x"],
            ["http://a/b/c/d;p?q", "g;x?y#s", "http://a/b/c/g;x?y#s"],
            ["http://a/b/c/d;p?q", "", "http://a/b/c/d;p?q"],
            ["http://a/b/c/d;p?q", ".", "http://a/b/c/"],
            ["http://a/b/c/d;p?q", "./", "http://a/b/c/"],
            ["http://a/b/c/d;p?q", "..", "http://a/b/"],
            ["http://a/b/c/d;p?q", "../", "http://a/b/"],
            ["http://a/b/c/d;p?q", "../g", "http://a/b/g"],
            ["http://a/b/c/d;p?q", "../..", "http://a/"],
            ["http://a/b/c/d;p?q", "../../", "http://a/"],
            ["http://a/b/c/d;p?q", "../../g", "http://a/g"],
        ];
    }

    /**
     * @dataProvider provideResolvables
     */
    public function testResolve($baseUri, $toResolve, $expectedResult) {
        $baseUri = new Uri($baseUri);
        $this->assertEquals($expectedResult, $baseUri->resolve($toResolve));
    }

    public function provideUris() {
        return [
            [
                'rawUri' => 'http://www.google.com/somePath?var=42#myFrag',
                'expectedVals' => [
                    'scheme' => 'http',
                    'user' => '',
                    'pass' => '',
                    'host' => 'www.google.com',
                    'port' => 80,
                    'path' => '/somePath',
                    'query' => 'var=42',
                    'fragment' => 'myFrag',
                ],
            ],
            [
                'rawUri' => 'http://localhost:80',
                'expectedVals' => [
                    'scheme' => 'http',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 80,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'https://localhost:443',
                'expectedVals' => [
                    'scheme' => 'https',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 443,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'ftp://localhost:21',
                'expectedVals' => [
                    'scheme' => 'ftp',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 21,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'ftps://localhost:990',
                'expectedVals' => [
                    'scheme' => 'ftps',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 990,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'smtp://localhost:25',
                'expectedVals' => [
                    'scheme' => 'smtp',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 25,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'http://someuser:mypass@localhost:8080/#frag',
                'expectedVals' => [
                    'scheme' => 'http',
                    'user' => 'someuser',
                    'pass' => 'mypass',
                    'host' => 'localhost',
                    'port' => 8080,
                    'path' => '/',
                    'query' => '',
                    'fragment' => 'frag',
                ],
            ],
            [
                'rawUri' => 'http://192.168.1.1/?q=42',
                'expectedVals' => [
                    'scheme' => 'http',
                    'user' => '',
                    'pass' => '',
                    'host' => '192.168.1.1',
                    'port' => 80,
                    'path' => '/',
                    'query' => 'q=42',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'tcp://[fe80::1]:80',
                'expectedVals' => [
                    'scheme' => 'tcp',
                    'user' => '',
                    'pass' => '',
                    'host' => '[fe80::1]',
                    'port' => 80,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'tcp://[fe80::1]',
                'expectedVals' => [
                    'scheme' => 'tcp',
                    'user' => '',
                    'pass' => '',
                    'host' => '[fe80::1]',
                    'port' => 0,
                    'path' => '',
                    'query' => '',
                    'fragment' => '',
                ],
            ],
            [
                'rawUri' => 'http://localhost/test.php?params[]=1&params[]=2',
                'expectedVals' => [
                    'scheme' => 'http',
                    'user' => '',
                    'pass' => '',
                    'host' => 'localhost',
                    'port' => 80,
                    'path' => '/test.php',
                    'query' => "params[]=1&params[]=2",
                    'fragment' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideUris
     */
    public function testUri($rawUri, $expectedVals) {
        $uri = new Uri($rawUri);
        $this->assertEquals($expectedVals['scheme'], $uri->getScheme());
        $this->assertEquals($expectedVals['host'], $uri->getHost());
        $this->assertEquals($expectedVals['user'], $uri->getUser());
        $this->assertEquals($expectedVals['pass'], $uri->getPass());
        $this->assertEquals($expectedVals['port'], $uri->getPort());
        $this->assertEquals($expectedVals['path'], $uri->getPath());
        $this->assertEquals($expectedVals['query'], $uri->getQuery());
        $this->assertEquals($expectedVals['fragment'], $uri->getFragment());
    }

    public function testNormalizeEmptyUri() {
        $uri = new Uri('');
        $this->assertEquals('', $uri->normalize());
    }

    public function testNormalizeBasicUri() {
        $uri = new Uri('http://google.com.tw/');
        $this->assertEquals('http://google.com.tw/', $uri->normalize());
    }

    public function testQueryParams() {
        $uri = new Uri('http://localhost/test.php?params=1&params=2');
        $this->assertEquals(['params' => [1, 2]], $uri->getAllQueryParameters());
    }

    public function testGetAuthority() {
        $uri = new Uri('http://www.google.com.tw:54467');
        $this->assertEquals('www.google.com.tw:54467', $uri->getAuthority());
    }

    public function testGetAuthorityWithPassword() {
        $uri = new Uri('ssh://username:password@example.com:123');
        $this->assertEquals('password', $uri->getPass());
    }

    public function testIsIpV4() {
        $uri = new Uri('http://127.0.0.1');
        $this->assertTrue($uri->isIpV4());
    }

    public function testIsIpV6() {
        $uri = new Uri('http://::ffff:c0a8:1');
        $this->assertTrue($uri->isIpV6());
    }

    public function testHasQueryParameter() {
        $uri = new Uri('http://www.google.com.tw?parameter=value');
        $this->assertTrue($uri->hasQueryParameter('parameter'));
    }

    public function testGetQueryParameter() {
        $uri = new Uri('http://www.google.com.tw?parameter=value');
        $this->assertEquals('value', $uri->getQueryParameter('parameter'));
        $this->assertNull($uri->getQueryParameter('no'));
    }

    public function testGetQueryParameterArray() {
        $uri = new Uri('http://www.google.com.tw?parameter=value');
        $this->assertSame(['value'], $uri->getQueryParameterArray('parameter'));
        $this->assertCount(0, $uri->getQueryParameterArray('no'));
    }

    public function testIsValidDnsName() {
        $this->assertTrue(isValidDnsName('google.com'));
        $this->assertFalse(isValidDnsName('google.com.'));
    }

    public function testInvalidUriException() {
        $this->expectException(InvalidUriException::class);
        new Uri('http://:80');
    }

    public function testGetOriginalUri() {
        $uri = new Uri('http://google.com.tw/../test');
        $this->assertEquals('http://google.com.tw/../test', $uri->getOriginalUri());
    }

    public function testIsValid() {
        $uri = new Uri('http://google.com.tw');
        $this->assertTrue($uri::isValid('http://google.com.tw'));
        $this->assertFalse($uri::isValid('http://:80'));
    }

    public function testGetAbsoluteUri() {
        $uri = new Uri('http://www.google.com.tw');
        $this->assertEquals('http://www.google.com.tw', $uri->getAbsoluteUri());
    }

    public function testInvalidDnsNameException() {
        $this->expectException(InvalidDnsNameException::class);
        normalizeDnsName('google.com.');
    }
}
