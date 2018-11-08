<?php

namespace MercesLab\Component\SpreadsheetClient\tests;

use MercesLab\Component\SpreadsheetClient\ClientInterface;
use MercesLab\Component\SpreadsheetClient\Google\GoogleSheet;
use MercesLab\Component\SpreadsheetClient\Google\GoogleTable;

/**
 * Class GoogleTableTest
 * @package MercesLab\Component\SpreadsheetClient\tests
 */
class GoogleTableTest extends AbstractClientTest
{
    const FILE_ID = 'foo';

    /** @var \MercesLab\Component\SpreadsheetClient\Google\GoogleTable */
    private $googleTable;

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadDefault()
    {
        $pageRange = 'A1:A';

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleTable->read();
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadOffset()
    {
        $pageRange  = 'A21:A';
        $offset     = 20;

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleTable->read($offset);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\ReadRequestFailedException
     */
    public function testReadLimit()
    {
        $pageRange  = 'A21:A31';
        $offset     = 20;
        $limit      = 10;

        $this->setupTestRead(self::FILE_ID, ClientInterface::DEFAULT_SHEET, $pageRange);
        $this->googleTable->read($offset, $limit);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteDefault()
    {
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, ClientInterface::DEFAULT_SHEET, ClientInterface::DEFAULT_RANGE);
        $this->googleTable->write($data);
    }

    /**
     * @throws \MercesLab\Component\SpreadsheetClient\Exception\WriteRequestFailedException
     */
    public function testWriteRange()
    {
        $data  = ['lorem', 'ipsum'];

        $this->setupTestWrite($data, self::FILE_ID, ClientInterface::DEFAULT_SHEET, ClientInterface::DEFAULT_RANGE);
        $this->googleTable->write($data);
    }

    public function setUp()
    {
        parent::setUp();

        $this->googleTable = new GoogleTable($this->googleServiceMock, self::FILE_ID);
    }
}
