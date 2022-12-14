<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Decks;
use App\Models\Cards;
use App\Models\CardRel;
use App\Models\Accounts;

class CardController extends Controller {

    public function show() {
        $user = Auth::user();
        if($user)
            $userDecks = Decks::where('account_id', $user->id)->get();
        else
            $userDecks = NULL;
        return view('browse', compact('user', 'userDecks'));
    }

    public function getAllCards() {
        $cards = Cards::distinct('card_name')->orderBy('card_name')->get();
        if(!$cards) {
            return response()->json(['error' => 'Card not found.'], 404);
        }
        return response()->json($cards);
    }

    public function getCardByName(Request $request) {
        $card_name = $request->input('card_name');
        $cards = Cards::where('card_name', 'like', '%' . $card_name . '%')->orderBy('card_name')->limit(10000)->get();    
        if(!$cards) {
            return response()->json(['error' => 'Card not found.'], 404);
        }
        return response()->json($cards);
    }

    public function colorFunction($colors) {
        $colorCount = count($colors);
        
        if($colorCount == 1) {
            $cards = Cards::where('colors', $colors[0])->orderBy('card_name');
            return $cards;
        } 
        if($colorCount == 2) {
            $cards = Cards::where('colors', 'like', '%' . $colors[0] . '%')
                          ->where('colors', 'like', '%' . $colors[1] . '%')
                          ->whereRaw('LENGTH(colors) = ?', strlen($colors[0]) + strlen($colors[1]))
                          ->orderBy('card_name');
            return $cards;
        }
        if($colorCount == 3) {
            $cards = Cards::where('colors', 'like', '%' . $colors[0] . '%')
                          ->where('colors', 'like', '%' . $colors[1] . '%')
                          ->where('colors', 'like', '%' . $colors[2] . '%')
                          ->whereRaw('LENGTH(colors) = ?', strlen($colors[0]) + strlen($colors[1]) + strlen($colors[2]))
                          ->orderBy('card_name');
            return $cards;
        }
        if($colorCount == 4) {
            $cards = Cards::where('colors', 'like', '%' . $colors[0] . '%')
                          ->where('colors', 'like', '%' . $colors[1] . '%')
                          ->where('colors', 'like', '%' . $colors[2] . '%')
                          ->where('colors', 'like', '%' . $colors[3] . '%')
                          ->whereRaw('LENGTH(colors) = ?', strlen($colors[0]) + strlen($colors[1]) + strlen($colors[2]) + strlen($colors[3]))
                          ->orderBy('card_name');
            return $cards;
        }
        if($colorCount == 5) {
            $cards = Cards::where('colors', 'like', '%' . $colors[0] . '%')
                          ->where('colors', 'like', '%' . $colors[1] . '%')
                          ->where('colors', 'like', '%' . $colors[2] . '%')
                          ->where('colors', 'like', '%' . $colors[3] . '%')
                          ->where('colors', 'like', '%' . $colors[4] . '%')
                          ->whereRaw('LENGTH(colors) = ?', strlen($colors[0]) + strlen($colors[1]) + strlen($colors[2]) + strlen($colors[3]) + strlen($colors[4]))
                          ->orderBy('card_name');
            return $cards;
        }
    }

    public function getCardsByColor(Request $request) {
        $colors = explode(",", $request->input('color'));
        $cards = $this->colorFunction($colors)->get();

        if(!$cards)
            return response()->json(['error' => 'No cards found.'], 404);
        return response()->json($cards);
    }

    public function getCardsBySet(Request $request) {
        $set = $request->input('card_set');
        $cards = Cards::where('card_set', $set)->orderBy('card_name')->get();
        if(!$cards) {
            return response()->json(['error' => 'No cards found.'], 404);
        }
        return response()->json($cards);
    }

    public function getCardsByType(Request $request) {
        $type = $request->input('type');
        $cards = Cards::where('type', 'like', '%' . $type .'%')->orderBy('card_name')->get();
        if(!$cards) {
            return response()->json(['error' => 'No cards found.'], 404);
        }
        return response()->json($cards);
    }

    public function getCardsByRarity(Request $request) {
        $rarity = $request->input('rarity');
        $cards = Cards::where('rarity', $rarity)->orderBy('card_name')->get();
        if(!$cards) {
            return response()->json(['error' => 'No cards found.'], 404);
        }
        return response()->json($cards);
    }

    public function getMultiFilter(Request $request) {
        $colors = NULL;
        if($request->has('color'))
            $colors = explode(",", $request->input('color'));

        $rarity = NULL;
        if($request->has('rarity'))
            $rarity = $request->input('rarity');

        $type = NULL;
        if($request->has('type'))
            $type = $request->input('type');

        $name = NULL;
        if($request->has('card_name'))
            $name = $request->input('card_name');

        if($colors && $rarity && $type && $name) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('type', 'like', '%' . $type .'%')
                          ->where('rarity', $rarity)
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $rarity && $type) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('type', 'like', '%' . $type .'%')
                          ->where('rarity', $rarity)
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $rarity && $name) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('rarity', $rarity)
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $type && $name) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('type', 'like', '%' . $type .'%')
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($rarity && $type && $name) {
            $cards = Cards::where('type', 'like', '%' . $type .'%')
                          ->where('rarity', $rarity)
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $rarity) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('rarity', $rarity)
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $name) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && $type) {
            $cards = $this->colorFunction($colors);
            $cards = $cards->where('type', 'like', '%' . $type .'%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($rarity && $type) {
            $cards = Cards::where('type', 'like', '%' . $type .'%')
                          ->where('rarity', $rarity)
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($rarity && $name) {
            $cards = Cards::where('rarity', $rarity)
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($type && $name) {
            $cards = Cards::where('type', 'like', '%' . $type .'%')
                          ->where('card_name', 'like', '%' . $name . '%')
                          ->orderBy('card_name')->get();
            return response()->json($cards);
        }

        if($colors && !$rarity && !$type) {
            return $this->getCardsByColor($request);
        }

        if($rarity && !$colors && !$type && !$name) {
            return $this->getCardsByRarity($request);
        }

        if($type && !$colors && !$rarity && !$name) {
            return $this->getCardsByType($request);
        }

        if($name && !$colors && !$rarity && !$type) {
            return $this->getCardByName($request);
        }

        return response()->json(['error' => 'No cards found.'], 404);
    }
}