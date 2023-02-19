<?php

/**
 * TekenAja REST API Library.
 *
 * @author     ANDREAS PANJAITAN
 * @license    MIT
 * @copyright  (c) 2022 Andreas Panjaitan
 * @email andreascandle89@gmail.com
 * @email andreas@singa.id
 */

namespace TekenAja;

use Unirest\Request;
use Unirest\Request\Body;

class Api
{

    /**
     * Curl Options.
     *
     * @var array $options
     */
    private static $curlOptions = array(
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSLVERSION => 6,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 60,
    );

    /**
     * Default Timezone.
     *
     * @var string
     */
    private static $timezone = 'Asia/Jakarta';

    /**
     * Default TekenAja Host.
     *
     * @var string
     */
    private static $hostName = 'apix.sandbox-111094.com';

    /**
     * Default TekenAja Host.
     *
     * @var string
     */
    private static $scheme = 'https';

    /**
     * Timeout curl.
     *
     * @var int
     */
    private static $timeOut = 60;

    private function headerConfig($contentType = 'application/json', )
    {
        $headers = array();
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = $contentType;
        $headers['apikey'] = $this->settings['apikey'];
        return $headers;
    }

    /**
     * Merge from existing array.
     *
     * @param array $existing_options
     * @param array $new_options
     * @return array
     */
    private static function mergeCurlOptions(&$existing_options, $new_options)
    {
        $existing_options = $new_options + $existing_options;
        return $existing_options;
    }

    /**
     * Default TekenAja Rest Settings
     *
     * @var array
     */
    protected $settings = array(
        'apikey' => '',
        'apiVersion' => '2',
        'scheme' => 'https',
        'timeout' => 60,
        'port' => 443,
        'timezone' => 'Asia/Jakarta',
        // New Options
        'options' => array(
            'host' => 'apix.sandbox-111094.com',
            'scheme' => 'https',
            'timeout' => 60,
            'port' => 443,
            'timezone' => 'Asia/Jakarta',
        ),
    );

    public function __construct($apikey = "", $options = [], $apiVersion = "2")
    {
        $this->settings['apikey'] = $apikey;
        $this->settings['apiVersion'] = $apiVersion;

        if (isset($options['host']) && $options['host']) {
            $this->settings['host'] = preg_replace('/http[s]?\:\/\//', '', $this->settings['host'], 1);
        }

        foreach ($options as $key => $value) {
            if (isset($this->settings[$key])) {
                $this->settings[$key] = $value;
            }
        }

        // Setup optional scheme, if scheme is empty
        if (isset($options['scheme'])) {
            $this->settings['scheme'] = $options['scheme'];
            $this->settings['options']['scheme'] = $options['scheme'];
        } else {
            $this->settings['scheme'] = self::getScheme();
            $this->settings['options']['scheme'] = self::getScheme();
        }

        // Setup optional host, if host is empty
        if (isset($options['host'])) {
            $this->settings['host'] = $options['host'];
            $this->settings['options']['host'] = $options['host'];
        } else {
            $this->settings['host'] = self::getHostName();
            $this->settings['options']['host'] = self::getHostName();
        }

        // Setup optional timezone, if timezone is empty
        if (isset($options['timezone'])) {
            $this->settings['timezone'] = $options['timezone'];
            $this->settings['options']['timezone'] = $options['timezone'];
        } else {
            $this->settings['timezone'] = self::getTimeZone();
            $this->settings['options']['timezone'] = self::getTimeZone();
        }

        // Setup optional timeout, if timeout is empty
        if (isset($options['timeout'])) {
            $this->settings['timeout'] = $options['timeout'];
            $this->settings['options']['timeout'] = $options['timeout'];
        } else {
            $this->settings['timeout'] = self::getTimeOut();
            $this->settings['options']['timeout'] = self::getTimeOut();
        }

        // Set Default Curl Options.
        Request::curlOpts(self::$curlOptions);

        // Set custom curl options
        if (!empty($this->settings['curl_options'])) {
            $data = self::mergeCurlOptions(self::$curlOptions, $this->settings['curl_options']);
            Request::curlOpts($data);
        }
    }

    public static function getHost()
    {
        return self::$hostName;
    }

    private function getProvincesCode($nik = "")
    {
        return substr($nik, 0, 2);
    }

    private function getDistrictCode($nik = "")
    {
        return substr($nik, 2, 2);
    }

    private function getSubDisctrictCode($nik = "")
    {
        return substr($nik, 4, 2);
    }

    /**
     * Set TimeZone.
     *
     * @param string $timeZone Time yang akan dipergunakan.
     *
     * @return string
     */
    public static function setTimeZone($timeZone)
    {
        self::$timezone = $timeZone;
        return self::$timezone;
    }

    /**
     * Get TimeZone.
     *
     * @return string
     */
    public static function getTimeZone()
    {
        return self::$timezone;
    }

    /**
     * Set nama domain TekenAja yang akan dipergunakan.
     *
     * @param string $hostName nama domain TekenAja yang akan dipergunakan.
     *
     * @return string
     */
    public static function setHostName($hostName)
    {
        self::$hostName = $hostName;

        return self::$hostName;
    }

    /**
     * Ambil nama domain TekenAja yang akan dipergunakan.
     *
     * @return string
     */
    public static function getHostName()
    {
        return self::$hostName;
    }

    /**
     * Ambil maximum execution time.
     *
     * @return string
     */
    public static function getTimeOut()
    {
        return self::$timeOut;
    }

    /**
     * Ambil nama domain TekenAja yang akan dipergunakan.
     *
     * @return string
     */
    public static function getCurlOptions()
    {
        return self::$curlOptions;
    }

    /**
     * Setup curl options.
     *
     * @param array $curlOpts
     * @return array
     */
    public static function setCurlOptions(array $curlOpts = [])
    {
        $data = self::mergeCurlOptions(self::$curlOptions, $curlOpts);
        self::$curlOptions = $data;

        // return.
        return self::$curlOptions;
    }

    /**
     * Set Ambil maximum execution time.
     *
     * @param int $timeOut timeout in milisecond.
     *
     * @return string
     */
    public static function setTimeOut($timeOut)
    {
        self::$timeOut = $timeOut;

        // return.
        return self::$timeOut;
    }

    /**
     * Set TekenAja Schema
     *
     * @param int $scheme Scheme yang akan dipergunakan
     *
     * @return string
     */
    public static function setScheme($scheme)
    {
        self::$scheme = $scheme;

        // return.
        return self::$scheme;
    }

    /**
     * Get TekenAja Schema
     *
     * @return string
     */
    public static function getScheme()
    {
        return self::$scheme;
    }

    private function getFullUlr()
    {
        return $this->settings['scheme'] . '://' . $this->settings['host'] . '/';
    }

    /**
     * Register
     * This module is for registering users into TekenAja
     *
     * @param string $email User Email Address
     * @param string $name Name of the user
     * @param boolean $gender Boolean (0 or 1), 0 = Female, 1 = Male
     * @param string $dob is the user's date of birth according to the E-KTP. Format in the form YYYY-MM-DD (example: 1990-02-27).
     * @param string $pob  is the place of birth of the user according to the E-KTP
     * @param string $nik is the user's NIK E-KTP number. NIK must be unique and has never been registered before
     * @param string $mobile  is the user's mobile number. Example of number format 0823000000.
     * @param string $address is the user's address
     * @param int $zip_code is the user's zip code.
     * @param string $ktp_photo is a user's E-KTP photo file. Approved formats are .jpg, .jpeg or.png. Maximum size 1MB
     * @param string $selfie_photo is the user's selfie photo file. Accepted formats are .jpg, .jpeg or .png. Maximum size 1MB.
     *
     * @return HttpResponse
     */
    public function register($email, $name, $gender, $dob, $pob, $nik, $mobile, $address, int $zip_code, $ktp_photo, $selfie_photo)
    {
        $headers = $this->headerConfig('multipart/form-data');
        $request_path = $this->getFullUlr() . "v2/register";

        $files = array('ktp_photo' => $ktp_photo, 'selfie_photo' => $selfie_photo);
        $data = array(
            'email' => $email,
            'name' => $name,
            'gender' => $gender,
            'dob' => $dob,
            'pob' => $pob,
            'nik' => $nik,
            'mobile' => $mobile,
            'province' => $this->getProvincesCode($nik),
            'district' => $this->getDistrictCode($nik),
            'sub_district' => $this->getSubDisctrictCode($nik),
            'address' => $address,
            'zip_code' => $zip_code,
        );

        $body = Body::Multipart($data, $files);        
        $response = Request::post($request_path, $headers, $body);

        return $response;
    }

    /**
     * REGISTER CHECK
     * This module is used to check user registration status and also to resend confirmation email after successful registration.
     *
     * @param string $nik is the user's NIK E-KTP number
     * @param string $action must be filled with the value “check_nik” or “resend_email
     * check_nik : to check status registration based on the NIK entered
     * resend_email : to resend the confirmation email sent to users who have
     * been successfully registered.
     *
     * @param string $email according to the email that was registered during the registration process (Optional).
     *
     * @return @var \Unirest\Response
     */

    public function registerCheck($nik = "", $action = "", $email = "")
    {
        $headers = $this->headerConfig('multipart/form-data');
        $request_path = $this->getFullUlr() . "v2/register-check";

        $data = array(
            'nik' => (string) $nik,
            'action' => (string) $action,        
        );

        if($email !== null || trim($email) !== ''){
            $data['email'] = (string) $email;
        }    

        $body = Body::Multipart($data);
        $response = Request::post($request_path, $headers, $body);
        return $response;
    }

    /**
     * Document Upload
     * This module is used to upload documents to be signed by the user.
     *
     * @param string path from PDF File to be sign
     * @param string $signature json String (all values in millimeters) Sample Value :
     * If there is 1 signer:
     * [{"email":"anton@mail.com","detail":[{"p":1,"x":200,"y":200,"w":200,"h":200},{"p":2,"x":200,"y":200,"w":200,"h":200}]}]
     * If there are 2 signers:
     * [{"email":"anton@mail.com","detail":[{"p":1,"x":200,"y":200,"w":200,"h":200},{"p":2,"x":200,"y":200,"w":200,"h":200}]},{"email":"ayu@mail.com","detail":[{"p":1,"x":500,"y":500,"w":200,"h":200}]}]
     * If a signature image is not needed in the contents of the document:
     * [{"email":"anton@mail.com"}]
     * p:page
     * x: horizontal top-left initial position point of the image (mm)
     * y: vertical top-left initial position point of the image (mm)
     * w: image width size (mm)
     * h: image height size (mm)
     *
     * been successfully registered.
     *
     * @param string $email according to the email that was registered during the registration process (Optional).
     *
     * @return string
     */
    public function documentUpload()
    {
        // On Progress

    }

}
