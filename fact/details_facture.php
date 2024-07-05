<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

// Obtenir les configurations par défaut
$configVariables = new ConfigVariables();
$defaultConfig = $configVariables->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$fontVariables = new FontVariables();
$defaultFontConfig = $fontVariables->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/custom/font/directory',
    ]),
    'fontdata' => $fontData + [
        'lato' => [
            'R' => 'Lato-Light.ttf',
            'I' => 'Lato-Bold.ttf',
        ]
    ],
    'default_font' => 'lato'
]);

// Sécurisation des entrées utilisateur
$factureNumber = htmlspecialchars($_GET['p'][0]);
$tva = htmlspecialchars($_GET['p'][1]);
$clientName = htmlspecialchars($_GET['p'][2]);
$email = htmlspecialchars($_GET['p'][3]);
$invoiceDate = htmlspecialchars($_GET['p'][4]);
$invoiceFrom = htmlspecialchars($_GET['p'][5]);
$designation1 = htmlspecialchars($_GET['p'][6]);
$quantity = htmlspecialchars($_GET['p'][7]);
$price = floatval($_GET['p'][8]);
$tax = floatval($_GET['p'][9]);
$designation2 = htmlspecialchars($_GET['p'][10]);

// Calculer le total
$total = $price + $tax;

// Contenu du PDF
$mpdf->WriteHTML('<h1 align="center" style="color: #343a40">Edition de la facture n° ' . $factureNumber . '</h1>');
$mpdf->WriteHTML('<hr style="color: #f0ad4e">');
$mpdf->WriteHTML('<br><br>');

$mpdf->WriteHTML('<p><strong>TVA à </strong>' . $tva . '</p>');
$mpdf->WriteHTML('<p><strong>NOM DU CLIENT</strong> ' . $clientName . '</p>');
$mpdf->WriteHTML('<p><strong>E-MAIL</strong> ' . $email . '</p>');

$mpdf->WriteHTML('<p><strong>FACTURE DE</strong> ' . $invoiceFrom . '</p>');
$mpdf->WriteHTML('<p><strong>DESIGNATION</strong> ' . $designation1 . '</p>');
$mpdf->WriteHTML('<p><strong>QUANTITE</strong> ' . $quantity . '</p>');
$mpdf->WriteHTML('<p><strong>PRIX</strong> ' . $price . '</p>');
$mpdf->WriteHTML('<p><strong>TAXE</strong> ' . $tax . '</p>');
$mpdf->WriteHTML('<p><strong>DESIGNATION</strong> ' . $designation2 . '</p>');

$mpdf->WriteHTML('<h2 align="right" style="color: #f0ad4e"><strong>TOTAL FACTURE</strong> ' . $total . ' € </h2>');

$mpdf->WriteHTML('<br><hr style="color: #f0ad4e"><br>');
$mpdf->WriteHTML('<p align="center" style="font-style:oblique"> DATE DE LA FACTURE ' . $invoiceDate . '</p>');

$mpdf->WriteHTML('<br><br>');
$mpdf->WriteHTML('<p align="center"><img src="assets/img/tampon-facture-acquittee.jpg" width="50%"></p>');

$mpdf->WriteHTML('<br>');
$mpdf->SetHTMLFooter('
<table width="100%">
    <tr>
        <td width="25%">Editer le {DATE j-m-Y}</td>
        <td width="50%" align="center" style="color: darkgray">FactWeb par <img src="assets/img/github_48px.png" width="2.5%">Lud972vic - Logiciel de devis & facture en ligne automatisé 100% Web Optimisez votre relation avec vos clients, vos fournisseurs et votre comptable…</td>
        <td width="25%" style="text-align: right;"> Fact n°' . $factureNumber . ' P{PAGENO}/{nbpg}</td>
    </tr>
</table>');

// Sortie du PDF
$mpdf->Output();
?>
