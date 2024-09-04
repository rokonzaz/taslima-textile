<?php

namespace App\Http\Controllers\Serve;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Creative;
use App\Models\CreativeImpression;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdServeController extends Controller
{
    public function index(Request $request, $zoneId)
    {
        $xmlContent = view('ad-serve.vast-ad-xml')->render();
        return new Response($xmlContent, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
    public function event(Request $request, $eventId)
    {
        $event=tripleBase64JsonDecode($eventId);

        if($event->eventOn=='creative'){
            $creativeImpression=CreativeImpression::find($event->creativeImpressionId);
            $currentTimeStamp=now();
            if($event->eventType=='impression') $creativeImpression->impression=$currentTimeStamp;
            if($event->eventType=='click') $creativeImpression->click=$currentTimeStamp;
            if($event->eventType=='clickThrough') {
                $creativeImpression->click_through = $currentTimeStamp;
                $creativeImpression->redirect_url = $event->redirectUrl ?? '';
            }
            if($event->eventType=='startAd') $creativeImpression->ad_start=$currentTimeStamp;
            if($event->eventType=='pauseAd') $creativeImpression->ad_pause=$currentTimeStamp;
            if($event->eventType=='skipAd') $creativeImpression->ad_skip=$currentTimeStamp;
            if($event->eventType=='completeAd') $creativeImpression->ad_complete=$currentTimeStamp;
            $creativeImpression->save();

            ///// Creative
            $creative = Creative::find($creativeImpression->creative_id);
            if($creative) {
                if ($event->eventType == 'impression') {
                    $creative->impressions += 1;
                    $creative->save();
                    return $creative;
                }
                if ($event->eventType == 'click') {
                    $creative->clicks += 1;
                    $creative->save();
                }
            }
            if($event->eventType=='clickThrough'){
                return redirect($event->redirectUrl);
            }


        }

    }
}
