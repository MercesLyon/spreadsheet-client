<?php

namespace MercesLab\Component\SpreadsheetClient\tests;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\Google\GoogleFile;

/**
 * Class GoogleFileTest
 * @package MercesLab\Component\SpreadsheetClient\tests
 */
class GoogleFileTest extends AbstractClientTest
{
    const FILE_ID = 'foo';

    /** @var \MercesLab\Component\SpreadsheetClient\Google\GoogleFile */
    private $googleFile;

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadDefault()
    {
        $pageRange = 'A1:A';
        $sheet     = ClientInterface::DEFAULT_SHEET;

        $this->setupTestRead(self::FILE_ID, $sheet, $pageRange);
        $this->googleFile->read();
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadTableRange()
    {
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A1:E';

        $this->setupTestRead(self::FILE_ID, $sheet, $pageRange);
        $this->googleFile->read($sheet, $tableRange);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadOffset()
    {
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E';
        $offset     = 20;

        $this->setupTestRead(self::FILE_ID, $sheet, $pageRange);
        $this->googleFile->read($sheet, $tableRange, $offset);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadLimit()
    {
        $sheet      = 'bar';
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E31';
        $offset     = 20;
        $limit      = 10;

        $this->setupTestRead(self::FILE_ID, $sheet, $pageRange);
        $this->googleFile->read($sheet, $tableRange, $offset, $limit);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteDefault()
    {
        $sheet = ClientInterface::DEFAULT_SHEET;
        $range = ClientInterface::DEFAULT_RANGE;
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, $sheet, $range);
        $this->googleFile->write($data);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteRange()
    {
        $sheet = 'bar';
        $range = 'E2:I2';
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, $sheet, $range);
        $this->googleFile->write($data, $sheet, $range);
    }

    public function setUp()
    {
        parent::setUp();

        $this->googleFile = new GoogleFile($this->googleServiceMock, self::FILE_ID);
    }
}
