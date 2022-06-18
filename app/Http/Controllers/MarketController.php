<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    public function index(Request $request)
    {

        $items = Item::latest();

        $advanced_sort = request('advanced');
        $sort = request('sort');
        $query = request('query');

        if($sort)
        {
            switch ($sort) {
                case 'shirts':
                    $type = 4;
                    break;
                case 'pants':
                    $type = 5;
                    break;
                case 'hats':
                    $type = 1;
                    break;
                case 'faces':
                    $type = 2;
                    break;
                case 'accessories':
                    $type = 3;
                    break;
                case 'tshirts':
                    $type = 6;
                    break;
            }

            $items->where('type', '=', $type);
        }

        if(!$sort && !$query)
        {
            $items->where('creator_id', '=', '1');
        }

        if($query)
        {
            $items->where('name', 'LIKE', "%{$query}%")->orWhere('desc', 'LIKE', "%{$query}%");
        }

        if(!$advanced_sort)
        {
            $items = $items->orderBy('updated_real', 'DESC');
        } elseif ($advanced_sort == 2)
        {
            $items = $items->orderBy('created_at', 'DESC');
        } elseif ($advanced_sort == 3)
        {
            $items = $items->orderBy('created_at', 'ASC');
        } elseif ($advanced_sort == 4)
        {
            $items = $items->orderBy('cash', 'ASC')->orderBy('coins', 'ASC');
        } elseif ($advanced_sort == 5)
        {
            $items = $items->orderBy('cash', 'DESC')->orderBy('coins', 'DESC');
        }

        $items = $items->paginate('12');

        if($request->ajax())
        {
            $view = view('components.load_market', compact('items'))->render();
            return response()->json(['html' => $view]);
        }

        return view('market.index', compact(['items']));
    }

    public function edit(Request $request, Item $item)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(30)))
        {
            return back();
        }

        if(auth()->user()->id != $item->owner->id)
            return abort(403);

        request()->validate([
            'title' => ['required', 'min:3', 'max:64'],
            'description' => ['max:2048'],
        ]);

        $cash = $request['cash'];
        $coins = $request['coins'];

        if($request['offsale'])
            $cash = 0;
            $coins = 0;

        $update = $item->update([
            'name' => $request['title'],
            'desc' => $request['description'],
            'cash' => $cash,
            'coins' => $coins,
        ]);

        return back();
    }

    public function edit_item(Request $request, Item $item)
    {
        $item = Item::find($item->id);

        if(!$item->exists())
        {
            abort(404);
        }

        return view('market.edit', compact(['item']));
    }

    public function show_item(Request $request, Item $item)
    {
        $item = Item::find($item->id);

        $comments = Comment::where('target_id', '=', $item->id)->where('scrubbed', '=', '0')->orderBy('id', 'DESC');
        if(!$item->exists()) {
            abort(404);
        }
        if($comments->get() != "[]")
        {
           $comments = $comments->paginate(10);
        } else {
            $comments = $comments->get();
        }

        return view('market.item', compact(['item', 'comments']));
    }

    public function create_item(Request $request)
    {
        return view('market.new');
    }

    public function create_shirt(Request $request)
    {
        return view('market.shirt');
    }

    public function create_pants(Request $request)
    {
        return view('market.pants');
    }

    public function upload_shirt(Request $request)
    {

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(30)))
        {
             return back();
        }

        $request->validate([
            'title' => 'required|min:3|max:64',
            'description' => 'max:2048',
            'image' => 'required|image|mimes:png|max:2048',
        ]);

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->extension();

        $request->image->storeAs('images', $imageName);

        $upload = Item::create([
            'name' => request('title'),
            'desc' => request('description'),
            'creator_id' => Auth::user()->id,
            'updated_real' => Carbon::now(),
            'type' => '4',
            'source' => $realName,
            'cash' => '0',
            'coins' => '0',
            'sales' => '0',
            'hash' => '1',
        ]);

        $grantItem = DB::table('inventories')->insert([
            'user_id' => Auth::user()->id,
            'item_id' => $upload->id,
            'type' => $upload->type,
        ]);

        return redirect(route('market.item', $upload->id));
    }

    public function upload_pants(Request $request)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(30)))
        {
            return back();
        }

        request()->validate([
            'image' => ['required', 'image', 'mimes:png', 'max:2048'],
            'title' => ['required', 'min:3', 'max:64'],
            'description' => ['max:2048'],
        ]);

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->getClientOriginalExtension();

        $request->image->storeAs('images', $imageName);

        $upload = Item::create([
            'name' => request('title'),
            'desc' => request('description'),
            'creator_id' => Auth::user()->id,
            'updated_real' => Carbon::now(),
            'type' => '5',
            'source' => $realName,
            'cash' => '0',
            'coins' => '0',
            'sales' => '0',
            'hash' => '1',
        ]);

        $grantItem = DB::table('inventories')->insert([
            'user_id' => Auth::user()->id,
            'item_id' => $upload->id,
            'type' => $upload->type,
        ]);

        return redirect(route('market.item', $upload->id));
    }

    public function comment(Request $request, Item $item)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(30)))
        {
            return back();
        }

        request()->validate([
            'body' => ['required', 'string', 'min:3', 'max:120'],
        ]);

        Comment::create([
            'user_id' => auth()->user()->id,
            'text' => $request['body'],
            'target_id' => $item->id,
        ]);

        return back();
    }

    public function buy_item(Item $item, $type)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(15)))
        {
            return back();
        }

        $user = Auth::user();
        $creator = $item->owner();

        // check ownership
        if($user->owns($item))
        {
            return back();
        }

        $amount = "";

        // cash
        if($type == 1)
        {
            if($user->cash >= $item->cash && $item->cash != 0)
            {
                $user->revoke_currency($item->cash, $type);
                $item->owner->grant_currency($item->cash, $type);

                $logPurchase = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'source_id' => $item->id,
                    'source_type' => '1',
                    'type' => '1',
                    'cash' => $item->cash,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $item->owner->id,
                    'source_id' => $item->id,
                    'source_type' => '1',
                    'type' => '2',
                    'cash' => $item->cash,
                ]);
                $grantItem = Inventory::create([
                    'user_id' => Auth::user()->id,
                    'item_id' => $item->id,
                    'type' => $item->type,
                ]);

                return back();
            }
        }
        // coins
        elseif($type == 2) {
            if($user->coins >- $item->coins && $item->coins != 0)
            {
                $user->revoke_currency($item->coins, $type);
                $item->owner->grant_currency($item->coins, $type);

                $logPurchase = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'source_id' => $item->id,
                    'source_type' => '1',
                    'type' => '1',
                    'coins' => $item->coins,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $item->owner->id,
                    'source_id' => $item->id,
                    'source_type' => '1',
                    'type' => '2',
                    'coins' => $item->coins,
                ]);
                $grantItem = Inventory::create([
                    'user_id' => Auth::user()->id,
                    'item_id' => $item->id,
                    'type' => $item->type,
                ]);

                return back();
            }
        }
    }
}
