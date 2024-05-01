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

        // if ($data['status'] <= 0 || empty($data)) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'No se pudo generar el certificado intentelo de nuevo mas tarde'
        //     ]);
        // }

        $data['pdf'] = 'JVBERi0xLjMKJeLjz9MKCjEgMCBvYmoKPDwKL1R5cGUgL0NhdGFsb2cKL091dGxpbmVzIDIgMCBSCi9QYWdlcyAzIDAgUgo+PgplbmRvYmoKCjIgMCBvYmoKPDwKL1R5cGUgL091dGxpbmVzCi9Db3VudCAwCj4+CmVuZG9iagoKMyAwIG9iago8PAovVHlwZSAvUGFnZXMKL0NvdW50IDIKL0tpZHMgWyA0IDAgUiA2IDAgUiBdIAo+PgplbmRvYmoKCjQgMCBvYmoKPDwKL1R5cGUgL1BhZ2UKL1BhcmVudCAzIDAgUgovUmVzb3VyY2VzIDw8Ci9Gb250IDw8Ci9GMSA5IDAgUiAKPj4KL1Byb2NTZXQgOCAwIFIKPj4KL01lZGlhQm94IFswIDAgNjEyLjAwMDAgNzkyLjAwMDBdCi9Db250ZW50cyA1IDAgUgo+PgplbmRvYmoKCjUgMCBvYmoKPDwgL0xlbmd0aCAxMDc0ID4+CnN0cmVhbQoyIEoKQlQKMCAwIDAgcmcKL0YxIDAwMjcgVGYKNTcuMzc1MCA3MjIuMjgwMCBUZAooIEEgU2ltcGxlIFBERiBGaWxlICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA2ODguNjA4MCBUZAooIFRoaXMgaXMgYSBzbWFsbCBkZW1vbnN0cmF0aW9uIC5wZGYgZmlsZSAtICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA2NjQuNzA0MCBUZAooIGp1c3QgZm9yIHVzZSBpbiB0aGUgVmlydHVhbCBNZWNoYW5pY3MgdHV0b3JpYWxzLiBNb3JlIHRleHQuIEFuZCBtb3JlICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA2NTIuNzUyMCBUZAooIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA2MjguODQ4MCBUZAooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA2MTYuODk2MCBUZAooIHRleHQuIEFuZCBtb3JlIHRleHQuIEJvcmluZywgenp6enouIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCApIFRqCkVUCkJUCi9GMSAwMDEwIFRmCjY5LjI1MDAgNjA0Ljk0NDAgVGQKKCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA1OTIuOTkyMCBUZAooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA1NjkuMDg4MCBUZAooIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlICkgVGoKRVQKQlQKL0YxIDAwMTAgVGYKNjkuMjUwMCA1NTcuMTM2MCBUZAooIHRleHQuIEFuZCBtb3JlIHRleHQuIEFuZCBtb3JlIHRleHQuIEV2ZW4gbW9yZS4gQ29udGludWVkIG9uIHBhZ2UgMiAuLi4pIFRqCkVUCmVuZHN0cmVhbQplbmRvYmoKCjYgMCBvYmoKPDwKL1R5cGUgL1BhZ2UKL1BhcmVudCAzIDAgUgovUmVzb3VyY2VzIDw8Ci9Gb250IDw8Ci9GMSA5IDAgUiAKPj4KL1Byb2NTZXQgOCAwIFIKPj4KL01lZGlhQm94IFswIDAgNjEyLjAwMDAgNzkyLjAwMDBdCi9Db250ZW50cyA3IDAgUgo+PgplbmRvYmoKCjcgMCBvYmoKPDwgL0xlbmd0aCA2NzYgPj4Kc3RyZWFtCjIgSgpCVAowIDAgMCByZwovRjEgMDAyNyBUZgo1Ny4zNzUwIDcyMi4yODAwIFRkCiggU2ltcGxlIFBERiBGaWxlIDIgKSBUagpFVApCVAovRjEgMDAxMCBUZgo2OS4yNTAwIDY4OC42MDgwIFRkCiggLi4uY29udGludWVkIGZyb20gcGFnZSAxLiBZZXQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqCkVUCkJUCi9GMSAwMDEwIFRmCjY5LjI1MDAgNjc2LjY1NjAgVGQKKCBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSApIFRqCkVUCkJUCi9GMSAwMDEwIFRmCjY5LjI1MDAgNjY0LjcwNDAgVGQKKCB0ZXh0LiBPaCwgaG93IGJvcmluZyB0eXBpbmcgdGhpcyBzdHVmZi4gQnV0IG5vdCBhcyBib3JpbmcgYXMgd2F0Y2hpbmcgKSBUagpFVApCVAovRjEgMDAxMCBUZgo2OS4yNTAwIDY1Mi43NTIwIFRkCiggcGFpbnQgZHJ5LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiBBbmQgbW9yZSB0ZXh0LiApIFRqCkVUCkJUCi9GMSAwMDEwIFRmCjY5LjI1MDAgNjQwLjgwMDAgVGQKKCBCb3JpbmcuICBNb3JlLCBhIGxpdHRsZSBtb3JlIHRleHQuIFRoZSBlbmQsIGFuZCBqdXN0IGFzIHdlbGwuICkgVGoKRVQKZW5kc3RyZWFtCmVuZG9iagoKOCAwIG9iagpbL1BERiAvVGV4dF0KZW5kb2JqCgo5IDAgb2JqCjw8Ci9UeXBlIC9Gb250Ci9TdWJ0eXBlIC9UeXBlMQovTmFtZSAvRjEKL0Jhc2VGb250IC9IZWx2ZXRpY2EKL0VuY29kaW5nIC9XaW5BbnNpRW5jb2RpbmcKPj4KZW5kb2JqCgoxMCAwIG9iago8PAovQ3JlYXRvciAoUmF2ZSBcKGh0dHA6Ly93d3cubmV2cm9uYS5jb20vcmF2ZVwpKQovUHJvZHVjZXIgKE5ldnJvbmEgRGVzaWducykKL0NyZWF0aW9uRGF0ZSAoRDoyMDA2MDMwMTA3MjgyNikKPj4KZW5kb2JqCgp4cmVmCjAgMTEKMDAwMDAwMDAwMCA2NTUzNSBmCjAwMDAwMDAwMTkgMDAwMDAgbgowMDAwMDAwMDkzIDAwMDAwIG4KMDAwMDAwMDE0NyAwMDAwMCBuCjAwMDAwMDAyMjIgMDAwMDAgbgowMDAwMDAwMzkwIDAwMDAwIG4KMDAwMDAwMTUyMiAwMDAwMCBuCjAwMDAwMDE2OTAgMDAwMDAgbgowMDAwMDAyNDIzIDAwMDAwIG4KMDAwMDAwMjQ1NiAwMDAwMCBuCjAwMDAwMDI1NzQgMDAwMDAgbgoKdHJhaWxlcgo8PAovU2l6ZSAxMQovUm9vdCAxIDAgUgovSW5mbyAxMCAwIFIKPj4KCnN0YXJ0eHJlZgoyNzE0CiUlRU9GCg==';

        return response()->json([
            'status' => true,
            'message' => 'Certificado generado correctamente',
            'pdf' => $data['pdf']
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
