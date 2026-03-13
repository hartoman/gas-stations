<?php
// initiates session so as to gain access to session variables
session_start();

// establishes contact with db
include 'dbh.php';

// init dom document
$dom = new DOMDocument();
// init global variable filename (string)
$fileName='';

// fills-up the data and creates file
// input: the name of the root element (case sensitive)
// input: the filepath of the dtd to check against
createXMLfile('business', './business.dtd');

// validates the DOMDocument after creation
// will proceed only if validation is successful
if (validateXML($dom)){
    
    // saves xml file of the DOMDocument on the server
    saveXMLFile($dom);

    // stylizes the DOMDocument with the associated xsl file
    stylizeXMLfile($dom,'./business.xsl');
}

// creates xml file
function createXMLfile($rootElementName, $dtdFilepath)
{
    // initiates dom implementation as basic template for the DOMDocument
    $implem = new DOMImplementation;
    
    // creates dom document type to attach to the DOMDocument
    $doctype = $implem->createDocumentType($rootElementName, '', $dtdFilepath);

    // initiates dom document
    $GLOBALS['dom'] = $implem->createDocument("", "", $doctype);
    $GLOBALS['dom']->encoding = 'UTF-8';

    // set the formatOutput attribute of domDocument to true to make it prettier
    $GLOBALS['dom']->formatOutput = true;

    // creates root
    $root = $GLOBALS['dom']->createElement('business');
    $GLOBALS['dom']->appendChild($root);

    // creates business name
    $bizname = $GLOBALS['dom']->createElement('bizname', $_SESSION["bizName"]);
    $root->appendChild($bizname);

    // creates afm
    $afm = $GLOBALS['dom']->createElement('afm', $_SESSION["bizAfm"]);
    $root->appendChild($afm);

    // creates county
    $county = $GLOBALS['dom']->createElement('county', $_SESSION["bizCounty"]);
    $root->appendChild($county);

    // creates municipality
    $municipal = $GLOBALS['dom']->createElement('municipal', $_SESSION["bizMunicipal"]);
    $root->appendChild($municipal);

    // creates address
    $address = $GLOBALS['dom']->createElement('address', $_SESSION["bizAddress"]);
    $root->appendChild($address);

    // creates offers
    $offers = $GLOBALS['dom']->createElement('offers');
    getOffers($offers);
    $root->appendChild($offers);
}

// serially adds the offers to the DOMDocument
function getOffers($offers)
{
    // search key is the afm
    $afm = $_SESSION["bizAfm"];

    // run db query on basis of the afm, which is unique
    $conn = OpenCon();
    $sql = "SELECT fuel_name,exp_date,price FROM fuel as f
    INNER JOIN offers as o
    ON f.fuel_id = o.offered_fuel
    INNER JOIN users as u
    ON o.offered_by = u.user_id
    WHERE AFM=$afm
    ;";
    $result = mysqli_query($conn, $sql);
    // closes off the db
    CloseCon($conn);

    // for every result create a single offer and append it to the offers
    while ($row = mysqli_fetch_row($result)) {

        $fuelType = $row[0];
        $expDate = $row[1];
        $price = $row[2];

        // creates empty single offer
        $singleoffer = $GLOBALS['dom']->createElement('offer');

        // creates fields
        $offeredFuel = $GLOBALS['dom']->createElement('fueltype', $fuelType);
        $offeredExpDate = $GLOBALS['dom']->createElement('expdate', $expDate);
        $offerPrice = $GLOBALS['dom']->createElement('price', $price);

        // field offerActive is true / false depending on the date
        if ($expDate < date('Y-m-d')) {
            // expiration date earlier than current date
            $offerActive = $GLOBALS['dom']->createElement('isactive', 'false');
        } else {
            // expiration date later or equal to current date
            $offerActive = $GLOBALS['dom']->createElement('isactive', 'true');
        }

        // appends fields to single offer element
        $singleoffer->appendChild($offeredFuel);
        $singleoffer->appendChild($offeredExpDate);
        $singleoffer->appendChild($offerPrice);
        $singleoffer->appendChild($offerActive);

        // appends single offer to all offers
        $offers->appendChild($singleoffer);
    }
    // empties the result set
    mysqli_free_result($result);
}

// validates the input DOMDocument, returns true/false + error message if false
function validateXML($dom)
{
    if ($dom->validate()) {
   //     echo "The xml document is valid!\n" . "<br>";
        return true;
    } else {
        echo "Invalid xml document\n";
        return false;
    }
}

// saves xml file of the DOMDocument on the server
function saveXMLFile($dom)
{
    /* creates xml file name with attached date */
    $date = date("d-m-Y");
    $fileName = './xml/' . $_SESSION["bizName"] . "_" . $date . '.xml';
    //  echo 'Created file on the server: ' . $fileName . "<br>";

    // saves the xml document on the server
    $dom->save($fileName);

    // passes the filename to the global variable with the same name
    $GLOBALS['fileName']=$fileName;
}

// applies the xsl formatting of the xsl-filename to the input dom
function stylizeXMLfile($dom,$XSLFilename){

    // Load XSL file
    $xsl = new DOMDocument();
    $xsl->load($XSLFilename);
    
    // init converter
    $proc = new XSLTProcessor();

    // Attach the xsl rules
    $proc->importStyleSheet($xsl);

    // init the stylized version of the dom
    $transformedDom = new DOMDocument();

    // transform input dom
    $transformedDom = $proc->transformToXML($dom);

    echo $transformedDom;
}

?>

<!-- link to download the file -->
<div>
<a href="<?php echo $fileName ?>"  download> Αποθήκευση των δεδομένων σε μορφή XML</a>
</div>