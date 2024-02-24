<?php

namespace App\Jobs;

use App\ApiRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecordAPiRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $apiRequest;
    protected $user;
    protected $url;
    protected $method;

    public function __construct($apiRequest = [], $url = null, $method = null, $user = null)
    {
        $this->apiRequest = $apiRequest;
        $this->user = $user;
        $this->method = $method;
        $this->url = $url;
    }

    public function handle()
    {
        $request = $this->apiRequest ? $this->apiRequest : [];
        $apiRequest = new ApiRequest();
        $apiRequest->setAttribute('data', json_encode($request));
        $apiRequest->setAttribute('url', $this->url);
        $apiRequest->setAttribute('method', $this->method);
        $apiRequest->setAttribute('user_id', $this->user->id ?? null);
        $apiRequest->save();
    }
}
