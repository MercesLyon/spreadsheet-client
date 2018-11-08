<?php

namespace MercesLab\Component\SpreadsheetClient\tests;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException;
use MercesLab\Component\SpreadsheetClient\Google\GoogleClient;

/**
 * Class GoogleClientTest
 * @package MercesLab\Component\SpreadsheetClient\tests
 */
class GoogleClientTest extends AbstractClientTest
{
    /** @var \MercesLab\Component\SpreadsheetClient\Google\GoogleClient */
    private $googleClient;

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadError()
    {
        /** @var \Google_Service_Sheets|\PHPUnit\Framework\MockObject\MockObject $googleServiceMock */
        $googleServiceMock = $this->createMock(\Google_Service_Sheets::class);
        $googleClient      = new GoogleClient($googleServiceMock);
        $range             = 'foobar';

        $this->expectException(ReadRequestFailedException::class);
        $googleClient->read('foo', 'bar', $range);

        $this->assertEquals("Could not parse range '$range'", $this->getExpectedException());
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadDefault()
    {
        $fileId    = 'foo';
        $pageRange = 'A1:A';
        $sheet     = ClientInterface::DEFAULT_SHEET;

        $this->setupTestRead($fileId, $sheet, $pageRange);
        $this->googleClient->read($fileId);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadTableRange()
    {
        $fileId     = 'foo';
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A1:E';

        $this->setupTestRead($fileId, $sheet, $pageRange);
        $this->googleClient->read($fileId, $sheet, $tableRange);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadOffset()
    {
        $fileId     = 'foo';
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E';
        $offset     = 20;

        $this->setupTestRead($fileId, $sheet, $pageRange);
        $this->googleClient->read($fileId, $sheet, $tableRange, $offset);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadLimit()
    {
        $fileId     = 'foo';
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E31';
        $offset     = 20;
        $limit      = 10;

        $this->setupTestRead($fileId, $sheet, $pageRange);
        $this->googleClient->read($fileId, $sheet, $tableRange, $offset, $limit);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteDefault()
    {
        $fileId = 'foo';
        $sheet  = ClientInterface::DEFAULT_SHEET;
        $range  = ClientInterface::DEFAULT_RANGE;
        $data   = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, $fileId, $sheet, $range);
        $this->googleClient->write($data, $fileId);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteRange()
    {
        $fileId = 'foo';
        $sheet  = 'bar';
        $range  = 'E2:I2';
        $data   = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, $fileId, $sheet, $range);
        $this->googleClient->write($data, $fileId, $sheet, $range);
    }

    public function setUp()
    {
        parent::setUp();

        $this->googleClient = new GoogleClient($this->googleServiceMock);
    }
}
