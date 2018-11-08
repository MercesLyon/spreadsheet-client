<?php

namespace MercesLab\Component\SpreadsheetClient\tests;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractClientTest
 * @package MercesLab\Component\SpreadsheetClient\tests
 */
abstract class AbstractClientTest extends TestCase
{
    /** @var \Google_Service_Sheets|\PHPUnit\Framework\MockObject\MockObject */
    protected $googleServiceMock;

    /**
     * @param string $fileId
     * @param string $sheet
     * @param string $pageRange
     */
    protected function setupTestRead(string $fileId, string $sheet, string $pageRange)
    {
        $googleSpreadsheetValuesMock = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        $googleValueRangeMock        = $this->createMock(\Google_Service_Sheets_ValueRange::class);

        $this->googleServiceMock->spreadsheets_values = $googleSpreadsheetValuesMock;
        $googleSpreadsheetValuesMock
            ->expects($this->once())
            ->method('get')
            ->with($fileId, "'$sheet'!$pageRange")
            ->willReturn($googleValueRangeMock)
        ;
        $googleValueRangeMock->expects($this->once())->method('getValues')->willReturn([]);
    }

    /**
     * @param array  $data
     * @param string $fileId
     * @param string $sheet
     * @param string $range
     */
    protected function setupTestWrite(array $data, string $fileId, string $sheet, string $range)
    {
        $googleSpreadsheetValuesMock = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);

        $this->googleServiceMock->spreadsheets_values = $googleSpreadsheetValuesMock;
        $googleSpreadsheetValuesMock
            ->expects($this->once())
            ->method('append')
            ->with(
                $fileId,
                "'$sheet'!$range",
                new \Google_Service_Sheets_ValueRange(['values' => [$data]]),
                ['valueInputOption' => 'USER_ENTERED']
            )
        ;
    }

    public function setUp()
    {
        $this->googleServiceMock = $this->createMock(\Google_Service_Sheets::class);
    }
}
