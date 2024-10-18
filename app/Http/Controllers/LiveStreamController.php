<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Aws\IVS\IVSClient;
use Illuminate\Support\Facades\Auth;

class LiveStreamController extends Controller
{
    protected $ivsClient;

    public function __construct()
    {
        // dd('ok');
        $this->middleware('auth');
            // dd($this->ivsClient);
            $this->ivsClient = new IVSClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function index()
    {
        return view('livestream');
    }

    public function joinStream(Request $request)
    {
        $playback_url = $request->query('playback_url');
        $post_id = $request->query('post_id');
        $post = Post::where('id', $post_id)->first();

    
        // dd($playback_url, $post_id); // For debugging, if needed
    
        return view('join-livestream', compact('playback_url', 'post_id', 'post'));
    }
    
    

    public function createStream()
    {
        $result = $this->ivsClient->createChannel([
            'latencyMode' => 'LOW',
            'name' => 'my_channel',
            'type' => 'BASIC',
        ]);

        return response()->json([
            'channel' => $result->get('channel'),
            'streamKey' => $result->get('streamKey')
        ]);
    }

    public function getStream(Request $request, $channelArn)
    {
        $result = $this->ivsClient->getChannel([
            'arn' => $channelArn
        ]);

        return response()->json([
            'channel' => $result->get('channel')
        ]);
    }
}
