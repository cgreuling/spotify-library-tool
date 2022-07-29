<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Kerox\OAuth2\Client\Provider\Spotify;

class SpotifyController extends Controller
{

    public function index(Spotify $spotify, Request $request)
    {
        if (! $request->has('code')) {
            $redirect_url = $spotify->getAuthorizationUrl([
                'scope' => config('services.spotify.scope')
            ]);

            $request->session()->put('spotify_state', $spotify->getState());

            return redirect($redirect_url);
        } elseif (empty($request->get('state')) || $request->get('state') !== $request->session()->get('spotify_state')) {
            $request->session()->forget('spotify_state');
            dd('Invalid state');
        }

        $token = $spotify->getAccessToken('authorization_code', [
            'code' => $request->get('code')
        ]);

        $request->session()->put('spotify_token', $token->getToken());

        return redirect()->route('spotify-library.index');
    }


}
