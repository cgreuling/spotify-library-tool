<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kerox\OAuth2\Client\Provider\Spotify;

class SpotifyLibraryController extends Controller
{
    protected string $baseUrl = 'https://api.spotify.com/v1';

    public function index(Request $request)
    {
        $token = $request->session()->get('spotify_token');
        $url = sprintf("%s/me/albums", $this->baseUrl);
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        $album_request = Http::asJson()
            ->withToken($token)
            ->get($url, [
                'limit' => $limit,
                'offset' => $offset
            ]);

        if ($album_request->successful()) {
            echo '<form method="post" action="' . route('spotify-library.remove-album') . '">';
            echo csrf_field();
            echo '<pre>';
            foreach ($album_request->json('items') as $item) {
                $checkbox = '<input type=checkbox name="ids[]" value="' . data_get($item, 'album.id') . '">';
                echo $checkbox . "\t" . ' ' . data_get($item, 'album.artists.0.name') . ' ' . data_get($item, 'album.name') . "\n";
            }
            echo '<button type=submit>Remove checked albums</button>' . "\n";
            echo '</form>' . "\n";

            if ($offset > 0) {
                echo '<a href="' . route('spotify-library.index', ['offset' => $offset - $limit, 'limit' => $limit]) . '">Previous</a> - ';
            }
            echo '<a href="' . route('spotify-library.index', ['offset' => $offset + $limit, 'limit' => $limit]) . '">Next</a>' . "\n";
        }
    }

    public function removeAlbum(Request $request)
    {
        $token = $request->session()->get('spotify_token');
        $url = sprintf("%s/me/albums", $this->baseUrl);

        $album_request = Http::asJson()
            ->withToken($token)
            ->delete($url, [
                'ids' => $request->get('ids')
            ]);

        dump($album_request->json());
    }
}
