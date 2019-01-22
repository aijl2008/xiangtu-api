<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/10
 * Time: 下午7:40
 */

namespace App\Models;


class Vod
{
    protected $secret_id = '';
    protected $secret_key = '';
    protected $api = 'https://vod.api.qcloud.com/v2/index.php';
    protected $guzzle = null;

    protected $params = [];

    function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        $this->secret_id = config("wechat.cloud.api.default.secret_id");
        $this->secret_key = config("wechat.cloud.api.default.secret_key");
        $this->params = [
            "SecretId" => $this->secret_id,
            "Timestamp" => time(),
            "Nonce" => rand(),
            "Region" => "bj",
            "SignatureMethod" => "HmacSHA256"
        ];
    }

    function getWaitingTaskList($file_id = null)
    {
        return $this->getTaskList("WAITING", $file_id);
    }

    function getProcessingTaskList($file_id = null)
    {
        return $this->getTaskList("PROCESSING", $file_id);
    }

    function getFinishedTaskList($file_id = null)
    {
        return $this->getTaskList("FINISH", $file_id);
    }

    private function getTaskList($status, $file_id = null)
    {
        $query = [
            "Action" => "GetTaskList",
            "status" => $status
        ];
        if ($file_id) {
            $query["fileId"] = $file_id;
        }

        $params = array_merge($this->params, $query);
        $signature = $this->signature($params);
        $video = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $video;
    }

    function getVideoInfo($file_id)
    {
        $query = [
            "Action" => "GetVideoInfo",
            "fileId" => $file_id
        ];

        $params = array_merge($this->params, $query);
        $signature = $this->signature($params);
        $video = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $video;
    }

    function runProcedure($param)
    {
        $query = [
            "Action" => "RunProcedure",
        ];

        $params = array_merge($this->params, $query, $param);
        $signature = $this->signature($params);
        $video = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $video;
    }

    function getTaskInfo($task_id)
    {
        $query = [
            "Action" => "GetTaskInfo",
            "vodTaskId" => $task_id
        ];

        $params = array_merge($this->params, $query);
        $signature = $this->signature($params);
        $video = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $video;
    }

    private function processFile($file_id, $param = [])
    {
        $query = [
            "Action" => "ProcessFile",
            "fileId" => $file_id,

        ];

        $params = array_merge($this->params, $query, $param);
        $signature = $this->signature($params);
        $video = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $video;
    }

    function setCoverBySnapshot($file_id)
    {
        return $this->processFile($file_id, [
            "coverBySnapshot.definition" => 10,
            "positionType" => "Percent",
            "position" => 10
        ]);
    }

    function convertVodFile($file_id, $param = [])
    {
        $query = [
            "Action" => "ConvertVodFile",
            "fileId" => $file_id,
            "isScreenshot" => 1,
            "isWatermark0" => 0
        ];

        $params = array_merge($this->params, $query, $param);
        $signature = $this->signature($params);
        $task = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $task;
    }

    function createSnapshotByTimeOffsetAsCover($file_id, $timeOffset = 1)
    {
        return $this->createSnapshotByTimeOffset($file_id, [
            "timeOffset.0" => $timeOffset,
        ]);
    }

    function createSnapshotByTimeOffset($file_id, $param = [])
    {
        $query = [
            "Action" => "CreateSnapshotByTimeOffset",
            "fileId" => $file_id,
            "definition" => 10,
            "timeOffset.0" => 1,
            "timeOffset.1" => 20
        ];

        $params = array_merge($this->params, $query, $param);
        $signature = $this->signature($params);
        $task = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        return $task;
    }

    function upload($file)
    {


        $common_params = [
            "SecretId" => $this->secret_id,
            "Timestamp" => time(),
            "Nonce" => rand(),
            "Region" => "bj",
            "SignatureMethod" => "HmacSHA256"
        ];

        $query = [
            "Action" => "ApplyUpload",
            "videoType" => "mp4"
        ];

        $params = array_merge($common_params, $query);
        $signature = $this->signature($params);
        $ApplyUpload = $this->send('GET', $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        print('ApplyUpload Success');
        print(var_export($ApplyUpload, true));

        /**
         * start upload
         */
        $url = "https://{$ApplyUpload->storageRegion}.file.myqcloud.com/files/v2/{$ApplyUpload->storageAppId}/{$ApplyUpload->storageBucket}{$ApplyUpload->video->storagePath}";
        $Uploaded = $this->send('POST', $url, [
            'headers' => [
                'Authorization' => $ApplyUpload->video->storageSignature
            ],
            'multipart' => [
                [
                    'name' => 'op',
                    'contents' => 'upload'
                ],
                [
                    'name' => 'filecontent',
                    'contents' => fopen($file, 'r')
                ]
            ]
        ]);
        print("Upload Success");
        print(var_export($Uploaded, true));

        /**
         * CommitUpload
         */


        $query = [
            "Action" => "CommitUpload",
            "vodSessionKey" => $ApplyUpload->vodSessionKey
        ];
        $params = array_merge($common_params, $query);
        $signature = $this->signature($params);
        $CommitUpload = $this->send("GET", $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        print('CommitUpload Success');
        print(var_export($CommitUpload, true));


        $query = [
            "Action" => "GetVideoInfo",
            "fileId" => $Uploaded->data->vid
        ];
        $params = array_merge($common_params, $query);
        $signature = $this->signature($params);
        $CommitUpload = $this->send("GET", $this->api . '?' . http_build_query(array_merge($params, ['Signature' => $signature])));
        print('GetVideoInfo Success');
        print(var_export($CommitUpload, true));
    }

    function send($method, $url, $options = [])
    {
        $parsed_url = parse_url($url);
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query);
        }
        \Illuminate\Support\Facades\Log::debug($url);
        $res = $this->guzzle->request($method, $url, $options);
        $content = $res->getBody()->getContents();
        \Illuminate\Support\Facades\Log::debug($content);
        return json_decode($content);
    }

    function signature($params)
    {
        $parsedUrl = parse_url($this->api);
        ksort($params);
        $signature_original_string = "GET{$parsedUrl['host']}{$parsedUrl['path']}?" . http_build_query($params);
        return base64_encode(hash_hmac('sha256', $signature_original_string, $this->secret_key, true));
    }
}