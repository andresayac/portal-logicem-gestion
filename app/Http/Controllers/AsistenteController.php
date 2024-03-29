<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hana;
use Illuminate\Support\Facades\Http;
use App\Models\AsistenteLog;
use Illuminate\Support\Facades\Crypt;

class AsistenteController extends Controller
{
    public function index()
    {

        return view('asistente.index');
    }

    public function proveedores()
    {
        $result = Hana::query('call "LOGICEM"."LOG_GetProv";');

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

    public function manifiestos(Request $request)
    {
        $cardcode = $request->get('cardcode');

        $result = Hana::query('call "LOGICEM"."LOG_GetManifiestos_Prov"(\'' . $cardcode . '\');');

        if ($result) {

            $index = 0;

            $result = array_map(function ($item) use (&$index) {
                $item['index'] = $index++;
                return $item;
            }, $result);

            return response()->json([
                'count' => count($result),
                'data' => $result
            ]);
        }

        return response()->json([
            'count' => 0,
            'data' => []
        ]);
    }


    public function sendManifiestos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 1800);

        $factura = $request->get('factura');
        $U_U_Comen_Fact = $request->get('U_U_Comen_Fact');
        $fecha = $request->get('fecha');
        $fecha = str_replace('/', '', $fecha);
        $manifiestos = $request->get('manifiestos');

        $lot = str_replace('.', '', uniqid('', true));

        try {
            // login
            $login_body = [
                'CompanyDB' => 'LOGICEM',
                'UserName' => auth()->user()->username,
                'Password' => Crypt::decryptString(auth()->user()->api_password),
                'Language' => '23',
            ];

            $response = Http::withoutVerifying()
                ->withOptions(['curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1']])
                ->post('https://10.238.22.165:50000/b1s/v1/Login', $login_body);

            $code = $response->status();

            if ($code != 200)
                throw new \Exception($response->body());

            // get cookies from login response
            $cookies = $response->cookies();
        } catch (\Throwable $th) {
            $login_error = $th->getMessage();
        }

        $request_body = [
            'U_U_Facturado' => "SI",
            'U_U_Num_Fact' => $factura,
            'U_U_Fecha_Factu' => $fecha,
            'U_U_Fe_Act_Fact' => date('Y-m-d h:i:s A'),
            'U_U_Comen_Fact' => $U_U_Comen_Fact,
        ];

        foreach ($manifiestos as $manifiesto) {

            $num_manifiesto = $manifiesto['Manifiesto'];

            $asistente_log = new AsistenteLog();
            $asistente_log->user_id = auth()->user()->id;
            $asistente_log->lot = $lot;
            $asistente_log->manifiesto = $num_manifiesto;
            $asistente_log->request_body = json_encode($request_body);

            if (isset($login_error)) {
                $asistente_log->request_body = json_encode($login_body);
                $asistente_log->response_message = $login_error;
                $asistente_log->save();
                continue;
            }

            try {
                $response = Http::withoutVerifying()
                    ->timeout(30)
                    ->withHeaders([
                        'Expect' => false
                    ])
                    ->withOptions([
                        'cookies' => $cookies,
                        'curl' => [CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1'],
                        'verify' => false,
                    ])
                    ->patch('https://10.238.22.165:50000/b1s/v1/U_HBT_TRANSMANIFIEST(\'' . $num_manifiesto . '\')', $request_body);

                $responseCode = $response->status();
                $asistente_log->response_body = $response->body();
                $asistente_log->response_code = $responseCode;
            } catch (\Throwable $th) {
                $asistente_log->response_message = $th->getMessage();
            }

            $asistente_log->save();
        }

        return response()->json([]);
    }

    public function log()
    {
        $asistente_logs = AsistenteLog::orderBy('id', 'desc')->paginate(10);;
        return view('asistente.log', compact('asistente_logs'));
    }

    public function logDetails(AsistenteLog $log)
    {
        return view('asistente.log_details', compact('log'));
    }
}
