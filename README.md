# PHP-Faxage

PHP-Faxage is a simple class for [Faxage.com](http://www.faxage.com)
You will need a Faxage account before you can use the class, as it requires a valid Username, Password and Company ID.

The API allows for sending content in multiple formats. But currently this class only sends the content in HTML format.

###Usage
    $faxage = new Faxage('YOUR_USERNAME','YOUR_COMPANY_ID','YOUR_PASSWORD');
    $faxage->set_fax_number('555-555-5555');
    $faxage->set_fax_content('<h1>Hello!</h1>');
    $faxage->set_recipient_name('Test McTester');
    $faxage->send_fax();
