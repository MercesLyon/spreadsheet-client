<?php

namespace MercesLab\Component\SpreadsheetClient\tests;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\Google\GoogleSheet;

/**
 * Class GoogleSheetTest
 * @package MercesLab\Component\SpreadsheetClient\tests
 */
class GoogleSheetTest extends AbstractClientTest
{
    const FILE_ID = 'foo';

    /** @var \MercesLab\Component\SpreadsheetClient\Google\GoogleSheet */
    private $googleSheet;

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadDefault()
    {
        $pageRange = 'A1:A';

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleSheet->read();
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadTableRange()
    {
        $tableRange = 'A1:E1';
        $pageRange  = 'A1:E';

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleSheet->read($tableRange);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadOffset()
    {
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E';
        $offset     = 20;

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleSheet->read($tableRange, $offset);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadLimit()
    {
        $tableRange = 'A1:E1';
        $pageRange  = 'A21:E31';
        $offset     = 20;
        $limit      = 10;

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleSheet->read($tableRange, $offset, $limit);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteDefault()
    {
        $range = ClientInterface::DEFAULT_RANGE;
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, ClientInterface::DEFAULT_SHEET, $range);
        $this->googleSheet->write($data);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteRange()
    {
        $range = 'E2:I2';
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, ClientInterface::DEFAULT_SHEET, $range);
        $this->googleSheet->write($data, $range);
    }

    public function setUp()
    {
        parent::setUp();

        $this->googleSheet = new GoogleSheet($this->googleServiceMock, self::FILE_ID);
    }
}
