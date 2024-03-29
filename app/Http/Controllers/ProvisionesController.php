<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hana;
use App\Models\ProvisionesLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class ProvisionesController extends Controller
{
    public function index()
    {
        return view('provisiones.index');
    }

    public function log()
    {
        $provisiones_log = ProvisionesLog::orderBy('id', 'desc')->paginate(10);;

        return view('provisiones.log', compact('provisiones_log'));
    }

    public function logDetails(ProvisionesLog $log)
    {
        return view('provisiones.log_details', compact('log'));
    }

    public function getClientes(Request $request)
    {
        $sql = 'select * from "LOGICEM"."VW_LOG_PROV_CLIENTES";';
        $result = Hana::query($sql);

        if ($result) {
            return response()->json([
                'count' => count($result),
                'options' => $result
            ]);
        }

        return response()->json([
            'count' => 0,
            'options' => [
                [
                    'CardCode' => '',
                    'CardName' => 'Error al obtener los clientes',
                ]
            ]
        ]);
    }

    public function getProvisiones(Request $request)
    {
        $sql = 'call "LOGICEM".SP_VW_LOG_GET_PROVISIONES(\'%s\',\'%s\',\'%s\')';

        $sql = sprintf(
            $sql,
            str_replace('/', '', $request->get('fecha_inicial')),
            str_replace('/', '', $request->get('fecha_final')),
            is_null($request->get('CardCode')) ? 'no' : $request->get('CardCode'),
        );

        $result = Hana::query($sql);

        if ($result === false) {
            return response()->json([], 500);
        }

        foreach ($result as $key => $value) {
            $result[$key]['index'] = $key + 1;
        }
        return response()->json($result, 200, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    private function addCreditOrDebit($journalEntry, $cuenta, $value)
    {
        if ($cuenta["U_Tipo_Cuenta"] === "Credito") {
            $journalEntry["Credit"] = $value >= 0 ? abs($value) : 0.0;
            $journalEntry["Debit"] = $value >= 0 ? 0.0 : abs($value);
        } else {
            $journalEntry["Debit"] = $value >= 0 ? abs($value) : 0.0;
            $journalEntry["Credit"] = $value >= 0 ? 0.0 : abs($value);
        }
        return $journalEntry;
    }

    public function sendProvisiones(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 1800);

        $provisiones = $request->get('provisiones');
        $fecha_asiento = $request->get('fecha_asiento');
        $fecha_inicial = $request->get('fecha_inicial');
        $fecha_final = $request->get('fecha_final');

        $sql = 'select * from "LOGICEM"."@LOG_PARAM_PROV";';
        $cuentas = Hana::query($sql);

        if ($cuentas === false || empty($cuentas))
            return response()->json(['error' => 'Error: No pudimos obtener la información de las cuentas desde nuestra API.'], 500);

        $cuenta_total = array_filter($cuentas, function ($cuenta) {
            return $cuenta['U_Cuenta'] == "130505002";
        });
        $cuenta_total = $cuenta_total[array_key_first($cuenta_total)];

        $cuenta_flete = array_filter($cuentas, function ($cuenta) {
            return $cuenta['U_Cuenta'] == "281505002";
        });
        $cuenta_flete = $cuenta_flete[array_key_first($cuenta_flete)];

        $cuenta_provision = array_filter($cuentas, function ($cuenta) {
            return $cuenta['U_Cuenta'] == "414505003";
        });
        $cuenta_provision = $cuenta_provision[array_key_first($cuenta_provision)];

        $primer_dia_mes_siguiente = Carbon::now()->addMonth()->firstOfMonth()->format('Y-m-d');

        $reference_date = Carbon::createFromFormat('Y/m/d', $fecha_asiento)->format('Y-m-d');

        $request_body = [
            "ReferenceDate" => $reference_date,
            "Memo" => "Provisiones " . $fecha_inicial . "-" . $fecha_final,
            "TransactionCode" => "PrRe",
            "UseAutoStorno" => "tYES",
            "StornoDate" => $primer_dia_mes_siguiente,
            "OriginalJournal" => "ttJournalEntry",
            "U_HBT_AreVal" => "Comun",
            "JournalEntryLines" => [],
        ];

        foreach ($provisiones as $provision) {
            // TOTAL
            $journalEntryTotal = [
                "AccountCode" => $cuenta_total["U_Cuenta"],
                "ShortName" => $cuenta_total["U_Cuenta"],
                "LineMemo" => "Provisión Remesas",
                "Reference1" => $provision["Remesa"],
                "Reference2" => $provision["Manifiesto"],
                "ProjectCode" => $cuenta_total["U_Proyecto"],
                "CostingCode" => !empty($provision["OcrCode"]) ? $provision["OcrCode"] : $cuenta_total["U_Dim1"],
                "CostingCode2" => !empty($provision["OcrCode2"]) ? $provision["OcrCode2"] : $cuenta_total["U_Dim2"],
                "CostingCode3" => !empty($provision["OcrCode3"]) ? $provision["OcrCode3"] : $cuenta_total["U_Dim3"],
                "U_HBT_Tercero" => $provision["CardCode"],
            ];
            $journalEntryTotal = $this->addCreditOrDebit($journalEntryTotal, $cuenta_total, floatval($provision["Valor Total"]));

            // FLETE
            $journalEntryFlete = [
                "AccountCode" => $cuenta_flete["U_Cuenta"],
                "ShortName" => $cuenta_flete["U_Cuenta"],
                "LineMemo" => "Provisión Remesas",
                "Reference1" => $provision["Remesa"],
                "Reference2" => $provision["Manifiesto"],
                "ProjectCode" => $cuenta_flete["U_Proyecto"],
                "CostingCode" => !empty($provision["OcrCode"]) ? $provision["OcrCode"] : $cuenta_flete["U_Dim1"],
                "CostingCode2" => !empty($provision["OcrCode2"]) ? $provision["OcrCode2"] : $cuenta_flete["U_Dim2"],
                "CostingCode3" => !empty($provision["OcrCode3"]) ? $provision["OcrCode3"] : $cuenta_flete["U_Dim3"],
                "U_HBT_Tercero" => $provision["CardCode"],
            ];
            $journalEntryFlete = $this->addCreditOrDebit($journalEntryFlete, $cuenta_flete, floatval($provision["Flete"]));

            // PROVISION
            $journalEntryProvision = [
                "AccountCode" => $cuenta_provision["U_Cuenta"],
                "ShortName" => $cuenta_provision["U_Cuenta"],
                "LineMemo" => "Provisión Remesas",
                "Reference1" => $provision["Remesa"],
                "Reference2" => $provision["Manifiesto"],
                "ProjectCode" => $cuenta_provision["U_Proyecto"],
                "CostingCode" => !empty($provision["OcrCode"]) ? $provision["OcrCode"] : $cuenta_provision["U_Dim1"],
                "CostingCode2" => !empty($provision["OcrCode2"]) ? $provision["OcrCode2"] : $cuenta_provision["U_Dim2"],
                "CostingCode3" => !empty($provision["OcrCode3"]) ? $provision["OcrCode3"] : $cuenta_provision["U_Dim3"],
                "U_HBT_Tercero" => $provision["CardCode"],
            ];
            $journalEntryProvision = $this->addCreditOrDebit($journalEntryProvision, $cuenta_provision, floatval($provision["Provision"]));

            array_push($request_body["JournalEntryLines"], $journalEntryTotal);
            array_push($request_body["JournalEntryLines"], $journalEntryFlete);
            array_push($request_body["JournalEntryLines"], $journalEntryProvision);
        }

        $provisiones_log = new ProvisionesLog();
        $provisiones_log->user_id = auth()->user()->id;
        $provisiones_log->request_body = json_encode($request_body);

        try {

            // login
            $response = Http::withoutVerifying()
                ->withOptions(['curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1']])
                ->post('https://10.238.22.165:50000/b1s/v1/Login', [
                    'CompanyDB' => 'LOGICEM',
                    'UserName' => auth()->user()->username,
                    'Password' => Crypt::decryptString(auth()->user()->api_password),
                    'Language' => '23',
                ]);

            // get cookies from login response
            $cookies = $response->cookies();

            // send provisiones with cookies
            $response = Http::withoutVerifying()
                ->timeout(300)
                ->withHeaders([
                    // 'Connection' => 'keep-alive',
                    // 'Accept' => '*/*',
                    // 'Accept-Encoding' => 'gzip, deflate, br',
                    // 'Content-Type' => 'application/json',
                    'Expect' => false
                ])
                ->withOptions([
                    'cookies' => $cookies,
                    'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
                    'verify' => false,
                ])
                ->post('https://10.238.22.165:50000/b1s/v1/JournalEntries', $request_body);

            $responseCode = $response->status();
            $provisiones_log->response_code = $responseCode;
            $provisiones_log->response_body = $response->body();
            $provisiones_log->save();
            return response()->json([]);
        } catch (\Throwable $th) {
            $provisiones_log->response_message = $th->getMessage();
            $provisiones_log->save();
            return response()->json(['error' => 'Error: No pudimos enviar estas provisiones a nuestra API.'], 500);
        }
    }

    public function logRemesas(ProvisionesLog $log)
    {
        return view('provisiones.log_remesas', compact('log'));
    }
}
