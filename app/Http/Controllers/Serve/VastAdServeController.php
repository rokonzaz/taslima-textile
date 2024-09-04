<?php

namespace App\Http\Controllers\Serve;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UrlGenerator\AdUrlGeneratorController;
use App\Models\CreativeImpression;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VastAdServeController extends Controller
{
    public $zone;
    public $request;

    public function __construct()
    {
        $this->zone = [];
        $this->request = request()->all();
    }

    public function serve()
    {
        $zone=$this->zone;
        $request=$this->request;
        //return $zone;
        $creatives = $zone->distinctCreativesOnlyHasFile();
        //$creative=$randomCreatives = $creatives->last();
        $creative=$randomCreatives = $creatives->random();
        $creativeImpression=new CreativeImpression();
        $creativeImpression->zone_id=$zone->id;
        $creativeImpression->campaign_id=$creative->campaign_id;
        $creativeImpression->creative_id=$creative->id;
        if($creativeImpression->save()){
            $urlGenerator=new AdUrlGeneratorController();
            $urlGenerator->creativeImpression=$creativeImpression;
            $impressionEventLink=$urlGenerator->vastImpressionEvent();
            $clickThrough=$urlGenerator->clickThrough();
            $clickTracking=$urlGenerator->clickTracking();
            $skipTrackingUrl=$urlGenerator->skipTrackingUrl();
            $startTrackingUrl=$urlGenerator->startTrackingUrl();
            $pauseTrackingUrl=$urlGenerator->pauseTrackingUrl();
            $completeTrackingUrl=$urlGenerator->completeTrackingUrl();

            $videoDetails=json_decode($creative->file_details);
            $skip=true;
            $skipOffset='00:00:10';
            $xmlContent = view('ad-serve.vast-ad-xml', compact(
                'creativeImpression',
                'creative',
                'impressionEventLink',
                'clickThrough',
                'clickTracking',
                'skip',
                'skipOffset',
                'skipTrackingUrl',
                'startTrackingUrl',
                'pauseTrackingUrl',
                'completeTrackingUrl',
                'videoDetails'
            ))->render();
            return new Response($xmlContent, 200, [
                'Content-Type' => 'application/xml',
            ]);
        }


        /*$urlGenerator=new AdUrlGeneratorController();
        $urlGenerator->zone=$zone;
        $urlGenerator->creative=$creative;

        $impressionEventLink=$urlGenerator->vastImpressionEvent();
        $clickThrough=$urlGenerator->clickThrough();
        $clickTracking=$urlGenerator->clickTracking();*/

    }
}
