namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;

class PDFController extends Controller
{
public function generatePDF(Request $request)
{
$emp = $request->input('emp');
$supplier_name = $request->input('supplier_name');
$spool = $request->input('spool');
$sack = $request->input('sack');
$box = $request->input('box');
$pallet = $request->input('pallet');

$dompdf = new Dompdf(array('is_unicode' => true));
$pdf = new Dompdf();
$pdf->loadHtml('
<html>

<head>
    <style>
        body {
            font-family: THSarabunNew, Arial, sans-serif;
        }

        h1 {
            color: red;
        }
    </style>
</head>

<body>
    <h1>'.$title.'</h1>
    <p>'.$supplier_name.'</p>
    <p>'.$spool.'</p>
    <p>'.$sack.'</p>
    <p>'.$box.'</p>
    <p>'.$pallet.'</p>
</body>

</html>
');
$pdf->render();
$pdf->stream();
}
}