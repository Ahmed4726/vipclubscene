<?php

namespace App\Http\Controllers;

use App\Post;
use AWS\CRT\Log;
use Aws\Exception\AwsException;
use Illuminate\Http\Request;
use Aws\Ivs\IvsClient;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Auth;

class IvsController extends Controller
{
    public function getIvsConfig()
    {
        $user = Auth::user();
        $profile = $user->profile;
        // dd($profile);
        $ingestEndpoint = null;
        $streamKeyvalue = null;
        $playback_url = null;
        $dateTime = now();
        if(empty($user->ingestEndpoint) || is_null($user->ingestEndpoint)  && is_null($user->streamKey) || empty($user->streamKey))
        {
        // Initialize IVS Client
        $ivsClient = new IvsClient([
            'region' => 'us-west-2',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Create a new IVS channel or get an existing one for the user
        $result = $ivsClient->createChannel([
            'name' => 'channel_for_user_' . $user->id,
            'type' => 'STANDARD',
            'latencyMode' => 'LOW',
            'recordingConfigurationArn' => 'arn:aws:ivs:us-west-2:474805516605:recording-configuration/Xua19j6o3Kbs',
        ]);
        // dd('ok');
        $channelARN = $result->get('channel')['arn'];
        // $id = $ivsClient->getStream([
        //     'channelArn' => $channelARN,
        // ]);
        // $stream = $id->get('stream');
        // dd($stream);
        // Retrieve recording configuration details using channel ARN
        $recordingConfigDetails = $this->getRecordingConfigDetails($ivsClient, $channelARN);

        // Extract bucket name from recording configuration
        $bucketName = $recordingConfigDetails['destinationConfiguration']['s3']['bucketName'];
        
        // Construct recording prefix or path
        $awsAccountID = $this->getAwsAccountIdFromArn($channelARN); // Function to extract AWS Account ID
        $channelID = $this->getChannelIdFromArn($channelARN); // Function to extract Channel ID
        $recordingPrefix = "ivs/v1/{$awsAccountID}/{$channelID}/"; // Customize this according to your needs

// dd($recordingPrefix);
        $channel = $result->get('channel');
        $ingestEndpoint = $channel['ingestEndpoint'];
        $playback_url = $channel['playbackUrl'];
        $streamKey = $result->get('streamKey');
        $streamKeyvalue = $streamKey['value'];

        $user->ingestEndpoint = $ingestEndpoint;
        $user->streamKey = $streamKeyvalue;
        $user->playback_url = $playback_url;
        $user->recordingPrefix = $recordingPrefix;
        
        $user->save();
        
        $profile->playback_url = $playback_url;
        $profile->save();
    }
    else
    {
        $ingestEndpoint = $user->ingestEndpoint;
        $streamKeyvalue = $user->streamKey;
        $playback_url = $user->playback_url;
    }
        return response()->json([
            'ingestEndpoint' => $ingestEndpoint,
            'streamKey' => $streamKeyvalue,
            'playback_url' => $playback_url,
        ]);
    }

    public function start_live(Request $request)
    {
        $user = auth()->user();
        $dateTime = now()->timezone('UTC')->format('Y/n/j/G/');
        // dd($dateTime);
        $prefix = auth()->user()->recordingPrefix.$dateTime;
        // dd($prefix);
        $recording_id = $this->getRecordingIdFromStreamId($prefix);
        // dd($recording_id);

        $profile = auth()->user()->profile;
        $profile->is_live = 1;
        
        $profile->save();

        $post = new Post();
        $post->text_content = $request->text_content;
        $post->media_type = 'live';
        $post->lock_type = $request->lock_type;
        $post->user_id = $user->id;
        $post->profile_id = $profile->id;
        $post->live_stream_url = $recording_id;

        $post->save();



        return response()->json([
            'message' => 'Stream is live now.',
            'recording_id' => $recording_id,
            'post_id' => $post->id
        ]);
    }

    public function end_live()
    {
        
        $profile = auth()->user()->profile;
        $profile->is_live = 0;
        
        $profile->save();

        return response()->json([
            'message' => 'Stream ended and saved successully.'
        ]);
    }

    private function getRecordingConfigDetails($ivsClient, $channelARN)
    {
        // Get the recording configuration details
        $result = $ivsClient->getRecordingConfiguration([
            'arn' => 'arn:aws:ivs:us-west-2:474805516605:recording-configuration/Xua19j6o3Kbs',
        ]);

        return $result->get('recordingConfiguration');
    }

    private function getAwsAccountIdFromArn($arn)
    {
        // Example implementation to extract AWS Account ID from ARN
        $arnParts = explode(':', $arn);
        return $arnParts[4]; // Assuming AWS Account ID is in the 5th position
    }

    // Helper method to extract Channel ID from ARN
    private function getChannelIdFromArn($arn)
    {
        // Example implementation to extract Channel ID from ARN
        $arnParts = explode('/', $arn);
        return end($arnParts); // Assuming Channel ID is the last part of the ARN after "/"
    }

    public function getRecordingIdFromStreamId($prefix)
    {
        $suffix = 'media/hls/master.m3u8';
        $s3Client = new S3Client([
            'region' => 'us-west-2',
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    
        $latestKey = null;
        $latestTimestamp = 0;
        $continuationToken = null;
    
        do {
            // Add a delay of 5 seconds
            sleep(5);
    
            $params = [
                'Bucket' => 'vipclubscene-stream',
                'Prefix' => $prefix
            ];
    
            if ($continuationToken) {
                $params['ContinuationToken'] = $continuationToken;
            }
    
            $result = $s3Client->listObjectsV2($params);
    
            $contents = $result['Contents'] ?? [];
    
            foreach ($contents as $object) {
                $key = $object['Key'];
                $lastModified = $object['LastModified']->getTimestamp();
    
                // Check if the object key ends with the desired suffix
                if (str_ends_with($key, $suffix)) {
                    // Compare timestamps to find the latest one
                    if ($lastModified > $latestTimestamp) {
                        $latestTimestamp = $lastModified;
                        $latestKey = $key;
                    }
                }
            }
    
            $continuationToken = $result['IsTruncated'] ? $result['NextContinuationToken'] : null;
        } while ($continuationToken);
    
        if ($latestKey !== null) {
            // Remove prefix and suffix to extract the recording ID
            $recordingIdPart = str_replace([$prefix, $suffix], '', $latestKey);
    
            // Combine prefix with the extracted recording ID
            $combinedResult = $prefix . $recordingIdPart . $suffix;
    
            // Log the combined result
            // Log::info('Combined Result:', ['combinedResult' => $combinedResult]);
    
            $rc_id = $combinedResult;
        } else {
            $rc_id = null;
        }
    
        // dd($rc_id);
        return $rc_id;
    }
    
    
    
    
    
    
    
    
}
