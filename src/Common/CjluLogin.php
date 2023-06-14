<?php
namespace FoskyTech\CjluAuth\Common;

class CjluLogin {
    public static function execute(string $server, string $user, string $pass): string
    {
        $curl = curl_init();
        $param = [
            'login_url' =>  'https://authserver.cjlu.edu.cn/authserver/login',
            'username'  =>  $user,
            'password'  =>  $pass
        ];
        curl_setopt_array($curl, [
            CURLOPT_URL => $server,
            CURLOPT_POSTFIELDS => http_build_query($param),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                'x-requested-with: XMLHttpRequest'
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            return false;
        }

        $data = json_decode($response);
        if ($data->code == 0) return true;
        return false;
    }
}
