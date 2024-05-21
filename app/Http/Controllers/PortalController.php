<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hana;
use App\Models\ProvisionesLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Traits\SapApi;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    use SapApi;

    public function certificadoRetencion()
    {
        return view('documentos.certificado-retencion');
    }

    public function certificadoRetencionPdf(Request $request)
    {
        $year_certificate = $request->year_certificate;
        $type_certificate = $request->type_certificate;

        if ($year_certificate == null || $type_certificate == null) {
            return response()->json([
                'message' => 'Los campos año y tipo de certificado son requeridos'
            ], 400);
        }

        $this->loginApiPdf();
        $data = $this->getGenerateCertificate(Auth::user()->username, $year_certificate, $type_certificate);
        // return base64 pdf

        if (empty($data)) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo generar el certificado intentelo de nuevo mas tarde'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Certificado generado correctamente',
            'pdf' => $data
        ]);
    }

    public function facturasRegistradas()
    {
        return view('documentos.facturas-registradas');
    }

    public function facturasRegistradasJson(Request $request)
    {
        $initial_date = $request->initial_date;
        $final_date = $request->final_date;

        if ($initial_date == null || $final_date == null) {
            return response()->json([
                'message' => 'Los campos fecha inicial y fecha final son requeridos'
            ], 400);
        }
        try {
            $this->login();
            $data = $this->getPurchaseInvoices(Auth::user()->username, $initial_date, $final_date);

            $data_response = [];
            $data_response['value'] = $data['value'];

            while (true) {
                if (isset($data['odata.nextLink'])) {
                    $skip = explode('skip=', $data['odata.nextLink'])[1];
                    $data = $this->getPurchaseInvoices(Auth::user()->username, $initial_date, $final_date, $skip);
                    $data_response['value'] = array_merge($data_response['value'], $data['value']);
                } else {
                    break;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Facturas registradas obtenidas correctamente',
                'data' => $data_response['value']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo obtener las facturas registradas'
            ]);
        }
    }

    public function pagosEfectuados()
    {
        return view('documentos.pagos-efectuados');
    }

    public function pagosEfectuadosJson(Request $request)
    {
        $initial_date = $request->initial_date;
        $final_date = $request->final_date;

        if ($initial_date == null || $final_date == null) {
            return response()->json([
                'message' => 'Los campos fecha inicial y fecha final son requeridos'
            ], 400);
        }
        try {
            $this->login();
            $data = $this->getPurchaseInvoices(Auth::user()->username, $initial_date, $final_date);

            $data_response = [];
            $data_response['value'] = $data['value'];

            while (true) {
                if (isset($data['odata.nextLink'])) {
                    $skip = explode('skip=', $data['odata.nextLink'])[1];
                    $data = $this->getPurchaseInvoices(Auth::user()->username, $initial_date, $final_date, $skip);
                    $data_response['value'] = array_merge($data_response['value'], $data['value']);
                } else {
                    break;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Facturas registradas obtenidas correctamente',
                'data' => $data_response['value']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo obtener las facturas registradas'
            ]);
        }
    }
}
