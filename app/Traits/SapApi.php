<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use App\Models\DocumentsLog;

trait SapApi
{
    protected $cookiesSap = '';
    protected $cookiesSapPdf = '';
    protected $settingsSap = [];

    protected function initializeAxios()
    {
        return Http::withOptions([
            'verify' => false,
            'timeout' => 8,
        ]);
    }

    protected function getSapSettings()
    {
        return [
            'SAP_COMPANY_DB' => config('app.sap.sap_company_db'),
            'SAP_USER' => config('app.sap.sap_user'),
            'SAP_PASSWORD' => config('app.sap.sap_password'),
            'SAP_LANGUAGE' => config('app.sap.sap_language'),
            'SAP_URL_WITH_PORT' => config('app.sap.sap_host'),
            'SAP_PDF_COMPANY_DB' => config('app.sap.sap_pdf_company_db'),
            'SAP_PDF_USER' => config('app.sap.sap_pdf_user'),
            'SAP_PDF_PASSWORD' => config('app.sap.sap_pdf_password'),
            'SAP_PDF_DB_INSTANCE' => config('app.sap.sap_pdf_db_instance'),
            'SAP_PDF_ENDPOINT' => config('app.sap.sap_pdf_endpoint'),
            'SAP_API_QUERY_ENDPOINT' => config('app.sap.sap_api_query'),
            'SAP_API_QUERY_AUTH_BASIC_USER' => config('app.sap.sap_api_query_auth_user'),
            'SAP_API_QUERY_AUTH_BASIC_PASSWORD' => config('app.sap.sap_api_query_auth_password'),
        ];
    }

    protected function login()
    {
        $this->settingsSap = $this->getSapSettings();
        $data = [
            "CompanyDB" => $this->settingsSap['SAP_COMPANY_DB'],
            "UserName" => $this->settingsSap['SAP_USER'],
            "Password" => $this->settingsSap['SAP_PASSWORD'],
            "Language" => $this->settingsSap['SAP_LANGUAGE']
        ];

        $response = $this->initializeAxios()->post($this->settingsSap['SAP_URL_WITH_PORT'] . '/b1s/v1/Login', $data);
        // $response->cookies(); to array
        $cookiesArray = $response->cookies()->toArray();

        // Convertir el array de cookies a una cadena
        $cookieString = '';
        foreach ($cookiesArray as $cookie) {
            $cookieString .= $cookie['Name'] . '=' . $cookie['Value'] . '; ';
        }

        // Eliminar el último ';' de la cadena de cookies
        $this->cookiesSap = rtrim($cookieString, '; ');

        return $response->json();
    }

    protected function getCustomerByNit($nit)
    {
        $url = $this->settingsSap['SAP_URL_WITH_PORT'] . "/b1s/v1/BusinessPartners?\$select=CardCode, CardName, EmailAddress,Cellular&\$filter=CardType eq 'S' and Frozen eq 'N' and Valid eq 'Y' and FederalTaxID eq '$nit'";
        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSap])->get($url);
        return $response->json();
    }

    protected function getPurchaseInvoicesOld($CardCode, $DocDateInitial, $DocDateEnd, $skip = 0)
    {
        $url = $this->settingsSap['SAP_URL_WITH_PORT'] . "/b1s/v1/PurchaseInvoices?\$select=DocEntry,DocNum,DocDate,DocDueDate,NumAtCard,VatSum,WTAmount,DocTotal,PaidToDate,DocCurrency&\$filter=CardCode eq '$CardCode' and DocDate ge '$DocDateInitial' and DocDate le '$DocDateEnd'&\$orderby=DocDate asc&\$skip=$skip";

        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSap])->get($url);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'purchase_invoices',
            'request_body' => json_encode([
                'CardCode' => $CardCode,
                'DocDateInitial' => $DocDateInitial,
                'DocDateEnd' => $DocDateEnd,
                'skip' => $skip
            ]),
            'response_body' => json_encode($response->json()),
            'response_code' => $response->status(),
        ]);
        return $response->json();
    }

    protected function getPurchaseInvoices($CardCode, $DocDateInitial, $DocDateEnd, $skip = 0)
    {
        $query = "select \"DocEntry\",\"DocNum\",\"DocDate\",\"DocDueDate\",\"NumAtCard\",\"VatSum\",\"WTSum\",\"DocTotal\",\"PaidToDate\",\"DocCur\" from \"LOGICEM\".OPCH where \"DocDate\" between '{$DocDateInitial}' and '{$DocDateEnd}' and \"CardCode\" = '{$CardCode}' order by \"DocDate\" asc ;";
        $response =  $this->execQuerySap($query);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'purchase_invoices',
            'request_body' => json_encode([
                'CardCode' => $CardCode,
                'DocDateInitial' => $DocDateInitial,
                'DocDateEnd' => $DocDateEnd,
                'skip' => $skip
            ]),
            'response_body' => json_encode($response->json()),
            'response_code' => $response->status(),
        ]);
        return $response->json();
    }

    protected function execQuerySap($query)
    {
        $this->settingsSap = $this->getSapSettings();

        $url = $this->settingsSap['SAP_API_QUERY_ENDPOINT'] . "/select";
        $body = [
            "script" => $query,
            "motor" => "HANA"
        ];

        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSap, 'Content-Type' => 'application/json'])->withBasicAuth($this->settingsSap['SAP_API_QUERY_AUTH_BASIC_USER'], $this->settingsSap['SAP_API_QUERY_AUTH_BASIC_PASSWORD'])->post($url, $body);
        return $response;
    }

    protected function getPreSettlements($CardCode)
    {
        $query = "select * from \"_SYS_BIC\".\"Logicem/PRELIQUIDACION_MANIFIESTO\" where \"CodTenedor\" = '{$CardCode}' and \"Facturador\"='SI';";
        $response =  $this->execQuerySap($query);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'pre-settlements',
            'request_body' => json_encode([
                'CardCode' => $CardCode
            ]),
            'response_body' => json_encode($response->json()),
            'response_code' => $response->status(),
        ]);
        return $response->json();
    }

    protected function getPaymentsMade($CardCode, $DocDateInitial, $DocDateEnd)
    {
        $query = "call \"LOGICEM\".\"AUTOGESTION_GET_PAGOS\"('$DocDateInitial','$DocDateEnd','$CardCode');";
        $response =  $this->execQuerySap($query);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'payments_made',
            'request_body' => json_encode([
                'CardCode' => $CardCode,
                'DocDateInitial' => $DocDateInitial,
                'DocDateEnd' => $DocDateEnd
            ]),
            'response_body' => json_encode($response->json()),
            'response_code' => $response->status(),
        ]);
        return $response->json();
    }

    protected function getPaymentsMadeDetail($DocEntry)
    {
        $query = "call \"LOGICEM\".\"AUTOGESTION_GET_PAGOS_DET\"('{$DocEntry}');";
        $response =  $this->execQuerySap($query);
        return $response->json();
    }

    protected function getDetailsRetentions($DocEntry)
    {
        $query = "call \"LOGICEM\".\"AUTOGESTION_DETALLE_RETENCION\"('{$DocEntry}');";
        $response =  $this->execQuerySap($query);
        return $response->json();
    }

    // AUTOGESTION_VALIDA_PREV
    protected function validatePreview($CardCode)
    {
        $query = "call \"LOGICEM\".\"AUTOGESTION_VALIDA_PREV\"('{$CardCode}');";
        $response =  $this->execQuerySap($query);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'validate_preview',
            'request_body' => json_encode([
                'CardCode' => $CardCode
            ]),
            'response_body' => json_encode($response->json()),
            'response_code' => $response->status(),
        ]);
        return $response->json();
    }

    protected function loginApiPdf()
    {
        $this->settingsSap = $this->getSapSettings();
        $data = [
            "CompanyDB" => $this->settingsSap['SAP_PDF_COMPANY_DB'],
            "UserName" => $this->settingsSap['SAP_PDF_USER'],
            "Password" => $this->settingsSap['SAP_PDF_PASSWORD'],
            "DBInstance" => $this->settingsSap['SAP_PDF_DB_INSTANCE']
        ];

        $response = $this->initializeAxios()->post($this->settingsSap['SAP_PDF_ENDPOINT'] . '/login', $data);
        // $response->cookies(); to array
        $cookiesArray = $response->cookies()->toArray();

        // Convertir el array de cookies a una cadena
        $cookieString = '';
        foreach ($cookiesArray as $cookie) {
            $cookieString .= $cookie['Name'] . '=' . $cookie['Value'] . '; ';
        }

        // Eliminar el último ';' de la cadena de cookies
        $this->cookiesSapPdf = rtrim($cookieString, '; ');
        return $response->json();
    }

    protected function getAllUserProvider()
    {
        $this->settingsSap = $this->getSapSettings();

        $url = $this->settingsSap['SAP_API_QUERY_ENDPOINT'] . "/select";
        $body = [
            "script" => "select \"CardCode\", \"CardName\", \"E_Mail\", \"Cellular\" from \"LOGICEM\".\"OCRD\" where \"CardType\" = 'S' and \"frozenFor\"='N';",
            "motor" => "HANA"
        ];

        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSap, 'Content-Type' => 'application/json'])->withBasicAuth($this->settingsSap['SAP_API_QUERY_AUTH_BASIC_USER'], $this->settingsSap['SAP_API_QUERY_AUTH_BASIC_PASSWORD'])->post($url, $body);
        return $response->json();
    }

    protected function validatePreviewCertificate($CardCode)
    {
    }

    protected function getIcaCertificate($CardCode = '', $dateInit = '', $dateEnd = '')
    {
        return [
            [
                "name" => "CardCode",
                "type" => "xsd:string",
                "value" => [[$CardCode]]
            ],
            [
                "name" => "fechaini",
                "type" => "xsd:string",
                "value" => [[$dateInit]]
            ],
            [
                "name" => "fechafin",
                "type" => "xsd:string",
                "value" => [[$dateEnd]]
            ],
            [
                "name" => "Schema@",
                "type" => "xsd:string",
                "value" => [["LOGICEM"]]
            ]

        ];
    }

    protected function getReteFuenteCertificate($CardCode = '', $yearCertificate = '')
    {
        return [
            [
                "name" => "CardCode",
                "type" => "xsd:string",
                "value" => [[$CardCode]]
            ],
            [
                "name" => "periodo",
                "type" => "xsd:string",
                "value" => [[$yearCertificate]]
            ],
            [
                "name" => "Schema@",
                "type" => "xsd:string",
                "value" => [["LOGICEM"]]
            ]

        ];
    }

    protected function getGenerateCertificate($CardCode = '', $yearCertificate = '',  $dateInit = '', $dateEnd = '', $typeCertificate = '')
    {

        $body_certificate = [
            1 => $this->getIcaCertificate($CardCode, $dateInit, $dateEnd),
            4 => $this->getReteFuenteCertificate($CardCode, $yearCertificate)
        ];

        $code_certificate = [
            1 => 'RCRI0041',
            4 => 'RCRI0042'
        ];

        $url = $this->settingsSap['SAP_PDF_ENDPOINT'] . "/rs/v1/ExportPDFData?DocCode=" . $code_certificate[$typeCertificate];
        $data = $body_certificate[$typeCertificate];

        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSapPdf])->post($url, $data);
        DocumentsLog::create([
            'user_id' => auth()->user()->id,
            'document_type' => 'generate_certificate',
            'request_body' => json_encode([
                'CardCode' => $CardCode,
                'yearCertificate' => $yearCertificate,
                'typeCertificate' => $typeCertificate
            ]),
            'response_body' => json_encode($response->body()),
            'response_message' => $response->status(),
            'response_code' => $response->status(),
            'url' => $url,
            'response' => json_encode($response->body())
        ]);
        return $response->body();
    }

    protected function logoutPdf()
    {
        $url = $this->settingsSap['SAP_PDF_ENDPOINT'] . "/logout";
        $response = $this->initializeAxios()->withHeaders(['Cookie' => $this->cookiesSapPdf])->post($url);

        return $response->json();
    }

    protected function obscureEmail($email)
    {
        if ($email == null) return null;

        $email_parts = explode("@", $email);
        $email_parts[0] = substr($email_parts[0], 0, 3) . str_repeat("*", strlen($email_parts[0]) - 3);
        return implode("@", $email_parts);
    }

    protected function obscureMobile($mobile)
    {
        if ($mobile == null) return null;

        if (strlen($mobile) < 2) {
            return $mobile;
        }

        return substr($mobile, 0, 2) . str_repeat("*", strlen($mobile) - 4) . substr($mobile, -3);
    }
}
