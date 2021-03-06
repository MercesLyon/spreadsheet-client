Install
=======

    composer require merces-lab/spreadsheet-client
    
Google
======

Usage
-----

You'll need a Google Service Account to use the clients from this component.
For reference, the Google documentation about the steps described below: https://developers.google.com/identity/protocols/OAuth2ServiceAccount#delegatingauthority

If you don't already have a service account you can use for your application:
- Go to the Google developer console: https://console.developers.google.com
- Create a project for your application (top left corner nex to the Google APIs logo)
- Select your project
- Go to "Enable API and services" (top of the screen) and enable the "Google Sheets API"
- Go to "Credentials" (left menu)
- In the "Create Credentials" dropdown menu, chose "Service account key"
    - Choose a name for the service account.
    - The service account does not need a role to be able to access Google Sheets.
    - Select "JSON" as the "Key type"
- You will be prompted to download the credentials file. Save that file to your disk.
- Keep the Credentials page you should be redirected to open. You will need to copy the ID of the service account you just created in the next step.

Give the necessary authorization to your service account:
- Go to the admin console: http://admin.google.com/
- Go to Security > Advanced settings > Manage API client access
- Enter your service account ID as a Client Name and the https://www.googleapis.com/auth/spreadsheets API scope
- Click Authorize

You should now have a service account authorized to access the Google Sheets API.
You will need to share the document you want to import data from or export data to with the service account you just created.
To give your service account access to the document:
- Go to the Credentials page in the developer console
- Go to "Manage service accounts"
- Copy the email address of your service account
- Go to your Google Spreadsheet document
- Click "Share"
- In the "Invite people" field, enter the email address of your service account
- Uncheck "Notify people"
- Click "Send"

Google_Service_Sheets
---------------------

To instantiate a \Google_Service_Sheets, you will need the credentials from the JSON file provided in the create service account steps.
You can either have the file somewhere on your server file system, or have the content in an environment variable.

The \MercesLab\Component\SpreadsheetClient\ClientFactory helper class will create the Google Service for you. You will need to pass it the credentials, either as an array or as a JSON string.

The classes from this components depend on that \Google_Service_Sheets.

```php
    <?php
    
    $credentials = file_get_contents('/var/secrets/credentials.json'); // Get the content of the JSON credentials file generated by Google (either from a file or via an environment variable eg. $credentials = getenv('GOOGLE_CREDENTIALS'))
    $googleServiceSheets = \MercesLab\Component\SpreadsheetClient\Google\GoogleFactory::createServiceSheets($credentials);
```

Client
------

The client only depends on the GoogleServiceSheets and implements a write() and read() methods.
You should use the client if you need to access multiple google spreadsheet documents at once.

The read and write operation require the file ID, and an optional sheet name and range for the table

```php
    <?php
    
    /** @var \Google_Service_Sheets $googleServiceSheets */
    $client = \MercesLab\Component\SpreadsheetClient\Google\GoogleFactory::createClient($googleServiceSheets);
    $rows = $client->read(
        'fileId', /* ID of the google document */
        'Sheet1', /* Name of the sheet where your table is (default Sheet1) */
        'A1:H1', /* Range of the table header row (default A1) */
        0, /* Offset - number of rows to be ignored at the start of the table (default 0) */
        100 /* Limit - maximum number of rows to return (default -1 = unlimited) */
    );
    
    foreach ($rows as $row) {
        $myData = $row[0];
        // Process $row which is a sequential array
    }
    
    $data = ['foo', 'bar',];
  
    $client->write($data, 'fileId', 'Sheet1', 'A1:H1'); // range is optional, and should match the headers of the table into which you want to export your data. Leaving it blank lets Google auto determine where your data should go (which is fine if you only have one table in the spreadsheet)
```

File
------

The file depends on the GoogleServiceSheets and the fileId. It also implements a write() and read() methods.
You should use the file if you need to access the same spreadsheet document across multiple services.

The read and write operation require an optional sheet name and range for the table

```php
    <?php
    
    /** @var \Google_Service_Sheets $googleServiceSheets */
    $file = \MercesLab\Component\SpreadsheetClient\Google\GoogleFactory::createFile($googleServiceSheets, 'myFileId');
    $rows = $file->read(
        'Sheet1', /* Name of the sheet where your table is (default Sheet1) */
        'A1:H1', /* Range of the table header row (default A1) */
        0, /* Offset - number of rows to be ignored at the start of the table (default 0) */
        100 /* Limit - maximum number of rows to return (default -1 = unlimited) */
    );
    
    foreach ($rows as $row) {
        $myData = $row[0];
        // Process $row which is a sequential array
    }
    
    $data = ['foo', 'bar',];
  
    $file->write($data, 'Sheet1', 'A1:H1'); // range is optional, and should match the headers of the table into which you want to export your data. Leaving it blank lets Google auto determine where your data should go (which is fine if you only have one table in the spreadsheet)
```

Sheet
------

The sheet depends on the GoogleServiceSheets, the fileId, and an optional sheet name. It also implements a write() and read() methods.
You should use the sheet if you need to access the same sheet from the same spreadsheet document across multiple services.

The read and write operation require an optional range for the table

```php
    <?php
    
    /** @var \Google_Service_Sheets $googleServiceSheets */
    $sheet = \MercesLab\Component\SpreadsheetClient\Google\GoogleFactory::createSheet($googleServiceSheets, 'myFileId', 'mySheet');
    $rows = $sheet->read(
        'A1:H1', /* Range of the table header row (default A1) */
        0, /* Offset - number of rows to be ignored at the start of the table (default 0) */
        100 /* Limit - maximum number of rows to return (default -1 = unlimited) */
    );
    
    foreach ($rows as $row) {
        $myData = $row[0];
        // Process $row which is a sequential array
    }
    
    $data = ['foo', 'bar',];
  
    $sheet->write($data, 'A1:H1'); // range is optional, and should match the headers of the table into which you want to export your data. Leaving it blank lets Google auto determine where your data should go (which is fine if you only have one table in the spreadsheet)
```

File
------

The table depends on the GoogleServiceSheets, the fileId, and an optional sheet name and range. It also implements a write() and read() methods.
You should use the file if you need to access the same table across multiple services.

```php
    <?php
    
    /** @var \Google_Service_Sheets $googleServiceSheets */
    $table = \MercesLab\Component\SpreadsheetClient\Google\GoogleFactory::createTable($googleServiceSheets, 'myFileId', 'mySheet', 'A1:H1');
    $rows = $table->read(
        0, /* Offset - number of rows to be ignored at the start of the table (default 0) */
        100 /* Limit - maximum number of rows to return (default -1 = unlimited) */
    );
    
    foreach ($rows as $row) {
        $myData = $row[0];
        // Process $row which is a sequential array
    }
    
    $data = ['foo', 'bar',];
  
    $table->write($data);
```
