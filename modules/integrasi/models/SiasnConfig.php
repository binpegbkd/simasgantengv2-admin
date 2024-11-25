<?php

namespace app\modules\integrasi\models;

use Yii;
use yii\helpers\Json;
use app\modules\integrasi\models\SiasnDataUtama;
use app\modules\integrasi\models\SiasnSso;

/**
 * This is the model class for table "siasn_config".
 *
 * @property string $mode
 * @property string|null $consumer_key
 * @property string|null $consumer_secret
 * @property string|null $clien_id
 * @property string|null $token_key
 * @property string|null $token_key_exp
 */
class SiasnConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'siasn_config';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode'], 'required'],
            [['token_key'], 'string'],
            [['token_key_exp'], 'safe'],
            [['mode'], 'string', 'max' => 10],
            [['consumer_key', 'consumer_secret'], 'string', 'max' => 100],
            [['clien_id'], 'string', 'max' => 50],
            [['mode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mode' => 'Mode',
            'consumer_key' => 'Consumer Key',
            'consumer_secret' => 'Consumer Secret',
            'clien_id' => 'Clien ID',
            'token_key' => 'Token Key',
            'token_key_exp' => 'Token Key Exp',
        ];
    }

    public static function getTokenWSO2($mode = 'train'){

        $config = SiasnConfig::findOne($mode);
        $clien_id = $config['clien_id'];
        $token_key = $config['token_key'];
        $token_key_exp = $config['token_key_exp'];
        $consumer_key = $config['consumer_key'];
        $consumer_secret = $config['consumer_secret'];

        if(time() > strtotime($token_key_exp)){

            if ($mode == 'train') {
                $url = "https://training-apimws.bkn.go.id/oauth2/token";
            } else if ($mode == 'prod') {
                $url = "https://apimws.bkn.go.id/oauth2/token";
            }
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_USERPWD, "$consumer_key:$consumer_secret");
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_POSTFIELDS,"client_id=$consumer_key&grant_type=client_credentials");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            // receive server response ...
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if(($jsondata = curl_exec($curl)) === false){
                exit( 'Curl error: ' . curl_error($curl));
            }else{
                $obj = json_decode($jsondata, true);
                
                // $expired_time = date("Y-m-d H:i:s", (strtotime(date('Y-m-d H:i:s')) + $obj['expires_in']));
                $expired_time = date("Y-m-d H:i:s", (strtotime(date('Y-m-d H:i:s')) + 3600));
                
                if(isset($obj['access_token'])){
                    $config['token_key_exp'] = $expired_time;
                    $config['token_key'] = $obj['access_token'];
                    $config->save(false);
                }
            }
            curl_close ($curl);    
        } 
        
        return $config;
        // return \yii\helpers\Json::encode($config);
    }

    public static function getTokenSSO($username, $password, $mode = 'train'){

        $ws = SiasnConfig::findOne($mode);
        $client_id =  $ws['clien_id'];

        $sso = SiasnSso::findOne($username);
        if($sso === null){
            $sso = new SiasnSso(['user' => $username]);
            $token_sso_exp = '1900-00-00 00:00:00';
        }
        else  $token_sso_exp = $sso['token_sso_exp'];

        if(time() > strtotime($token_sso_exp)){

            if ($mode == 'train') {
                $url = 'https://iam-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token/';
            } else if ($mode == 'prod') {
                $url = 'https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token';
            }
            
            $grant_type = 'password';
            $post_data='client_id='.$client_id.'&grant_type=password&username='.$username.'&password='.$password;
           
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));   
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

            if(($jsondata = curl_exec($curl)) === false){
                exit( 'Curl error: ' . curl_error($curl));
            }else{
                $obj = json_decode($jsondata, true);                
                // $expired_time = date("Y-m-d H:i:s", (strtotime(date('Y-m-d H:i:s')) + $obj['expires_in']));
                $expired_time = date("Y-m-d H:i:s", (strtotime(date('Y-m-d H:i:s')) + 3600));
                
                if(isset($obj['access_token'])){
                    $sso['token_sso_exp'] = $expired_time;
                    $sso['token_sso'] = $obj['access_token'];
                    $sso->save(false);
                }
            }
            curl_close ($curl);
        }
        return $sso;
        // return \yii\helpers\Json::encode($sso);
    }

    public static function apiResult($mode, $tokenKey, $tokenSSO, $page, $nip){

        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$page.$nip;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$page.$nip;
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);
    }

    public static function apiResultPost($mode, $tokenKey, $tokenSSO, $nip, $page){

        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$page;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$page;
        }

        $data = json_encode($nip);
        // $data = $nip;
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        var_dump($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
    		'Content-Type: application/json');
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);
    }

    public static function apiDok($mode, $tokenKey, $tokenSSO, $path){

        $page = '/download-dok?filePath='.$path;
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$page;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$page;
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);
    }

    public static function postDok($mode, $tokenKey, $tokenSSO, $file, $ref){
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0/upload-dok';
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0/upload-dok';
            
        }

        $curlfile = curl_file_create($file);

        $data = array(
            'file' => $curlfile,
            'id_ref_dokumen' => $ref
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        var_dump($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
    		'Content-Type: multipart/form-data');
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);
        
    }

    public static function postDokRw($mode, $tokenKey, $tokenSSO, $file, $rw, $ref){
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0/upload-dok-rw';
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0/upload-dok-rw';
            
        }

        $curlfile = curl_file_create($file);

        $data = array(
            'file' => $curlfile,
            'id_riwayat' => $rw,
            'id_ref_dokumen' => $ref
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        var_dump($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
    		'Content-Type: multipart/form-data');
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);        
    }

    public static function postSave($mode, $tokenKey, $tokenSSO, $file, $rw){
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$file;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$file;
            
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/form-data'));
        var_dump($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        // curl_setopt($curl, CURLOPT_POST, true); 
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
            'Content-Type: '.$rw);
        //application/json
        //multipart/form-data
        //application/x-www-form-urlencoded

        // var_dump($arr_header);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);

        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);	        
    }

    function resultPost($mode, $file, $data, $jenis_konten, $tokenKey, $tokenSSO){
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$file;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$file;
            
        }        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/form-data'));
        // var_dump($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        // curl_setopt($curl, CURLOPT_POST, true); 
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
            'Content-Type: '.$jenis_konten);
        //application/json
        //multipart/form-data
        //application/x-www-form-urlencoded
    
        // var_dump($arr_header);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);	
    }

    function apiDelete($mode, $file, $jenis_konten, $tokenKey, $tokenSSO){
        
        if ($mode == 'train') {
            $url = 'https://training-apimws.bkn.go.id:8243/api/1.0'.$file;
        } else if ($mode == 'prod') {
            $url = 'https://apimws.bkn.go.id:8243/apisiasn/1.0'.$file;
            
        }        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/form-data'));
        // var_dump($data);
        // curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        // curl_setopt($curl, CURLOPT_POST, true); 
        $arr_header = array(
            'accept: application/json',
            'Auth: bearer '.$tokenSSO,
            'Authorization: Bearer '.$tokenKey,
            'Content-Type: '.$jenis_konten);
        //application/json
        //multipart/form-data
        //application/x-www-form-urlencoded
    
        // var_dump($arr_header);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $arr_header);
    
        // receive server response ...
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        return curl_exec($curl);	
    }
}
