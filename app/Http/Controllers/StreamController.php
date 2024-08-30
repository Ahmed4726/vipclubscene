<?php
namespace App\Http\Controllers;

use Aws\Sdk;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    protected $ivsClient;

    public function __construct()
    {
        // Directly access environment variables
        $awsAccessKeyId = env('AWS_ACCESS_KEY_ID');
        $awsSecretAccessKey = env('AWS_SECRET_ACCESS_KEY');
        $awsDefaultRegion = env('AWS_DEFAULT_REGION');

        // Debug output to check environment variables
        // dd($awsAccessKeyId, $awsSecretAccessKey, $awsDefaultRegion);

        // Set up AWS SDK
        $sdk = new Sdk([
            'credentials' => [
                'key'    => $awsAccessKeyId,
                'secret' => $awsSecretAccessKey,
            ],
            'region' => $awsDefaultRegion,
            'version' => 'latest',
        ]);

        $this->ivsClient = $sdk->createIvs();
    }

    public function listVideos()
    {
        $recordingArn = 'arn:aws:ivs:us-west-2:474805516605:recording-configuration/KEwMXOe512o4'; // Get recording ARN from the request

        try {
            // Get the details of the recording
            $result = $this->ivsClient->getRecordingConfiguration([
                'arn' => $recordingArn,
            ]);
            dd($result);
            // Retrieve the S3 bucket and object key from the recording details
            $s3Bucket = $result['recordingConfiguration']['destinationConfiguration']['s3']['bucketName'];
            // dd($s3Bucket);
            $s3ObjectKey = $result['recordingConfiguration']['destinationConfiguration']['s3']['objectKeyPrefix'] . 'master.m3u8';
            // dd($s3ObjectKey);
            // Construct the S3 URL
            $playbackUrl = "https://{$s3Bucket}.s3.amazonaws.com/{$s3ObjectKey}";

            return response()->json([
                'playbackUrl' => $playbackUrl,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
