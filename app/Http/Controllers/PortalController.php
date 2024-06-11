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
        $list_years =  $this->getListYears();
        return view('documentos.certificado-retencion', compact('list_years'));
    }

    protected function getListYears()
    {
        $current_year = Carbon::now()->year;
        $list_years = [];
        $list_years[] = $current_year;
        $list_years[] = $current_year - 1;

        return $list_years;
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

        $list_years =  $this->getListYears();

        if (!in_array($year_certificate, $list_years)) {
            return response()->json([
                'message' => 'El año seleccionado no es valido'
            ], 400);
        }

        try {
            $this->login();

            // validatePreview($CardCode)
            $check = $this->validatePreview(Auth::user()->username);

            if (isset($check[0]['Validacion']) && $check[0]['Validacion'] === false) {
                return response()->json([
                    'status' => false,
                    'validation' => false,
                    'message' => 'No se pudo generar el certificado contacte al administrador del sistema.'
                ], 400);
            }

            $this->loginApiPdf();
            $data = $this->getGenerateCertificate(Auth::user()->username, $year_certificate, $type_certificate);
            // return base64 pdf

            if (empty($data)) {
                return response()->json([
                    'status' => false,
                    'validation' => true,
                    'message' => 'No se pudo generar el certificado intentelo de nuevo mas tarde'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'validacion' => true,
                'message' => 'Certificado generado correctamente',
                'pdf' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'validacion' => true,
                'message' => 'No se pudo generar el certificado intentelo de nuevo mas tarde'
            ]);
        }
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
            $purchaseInvoices = $this->getPurchaseInvoices(Auth::user()->username, $initial_date, $final_date);

            if (isset($purchaseInvoices['status']) && $purchaseInvoices['status'] == 'no_data') {
                return response()->json([
                    'status' => true,
                    'message' => 'No tienes facturas registradas con los filtros seleccionados',
                    'data' => []
                ]);
            }

            foreach ($purchaseInvoices as &$purchaseInvoice) {
               $purchaseInvoice['details'] = $this->getDetailsRetentions($purchaseInvoice['DocEntry']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Facturas registradas obtenidas correctamente',
                'data' => $purchaseInvoices
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo obtener las facturas registradas',
                'error' => 'Error al obtener las facturas registradas'
            ], 400);
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
            $payments = $this->getPaymentsMade(Auth::user()->username, $initial_date, $final_date);

            if (isset($payments['status']) && $payments['status'] == 'no_data') {
                return response()->json([
                    'status' => true,
                    'message' => 'No tienes pagos efectuados registrados con los filtros seleccionados',
                    'data' => []
                ]);
            }

            foreach ($payments as $key => &$payment) {
                $payment['details'] = $this->getPaymentsMadeDetail($payment['DocEntry']);
                // recorrer details si DocNum es null eliminar
                foreach ($payment['details'] as $key => $detail) {
                    if ($detail['DocNum'] == null) {
                        unset($payment['details'][$key]);
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Pagos efectuados obtenidos correctamente',
                'data' => $payments
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo obtener los pagos efectuados'
            ], 400);
        }
    }

    public function preliquidaciones()
    {
        return view('documentos.preliquidaciones');
    }

    public function preliquidacionesJson(Request $request)
    {
        try {
            $this->login();
            $data = $this->getPreSettlements(Auth::user()->username);

            if (isset($data['status']) && $data['status'] == 'no_data') {
                return response()->json([
                    'status' => true,
                    'message' => 'No tienes preliquidaciones registradas con los filtros seleccionados',
                    'data' => []
                ]);
            }

            $data_response = [];
            $data_response['value'] = $data;

            return response()->json([
                'status' => true,
                'message' => 'Facturas registradas obtenidas correctamente',
                'data' => $data_response['value']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'No se pudo obtener las facturas registradas'
            ], 400);
        }
    }
}
