<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemData;
use App\Models\ItemReseller;
use App\Models\Transaction;
use Carbon\Carbon;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            $items = $items->orderBy('updated_real', 'DESC');
        } elseif ($advanced_sort == 3)
        {
            $items = $items->orderBy('updated_real', 'ASC');
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
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        if(auth()->user()->id != $item->owner->id)
            return abort(403);

        request()->validate([
            'title' => ['required', 'min:3', 'max:64'],
            'description' => ['max:2048'],
            'cash' => ['numeric', 'min:-1'],
            'coins' => ['numeric'],
        ]);

        $cash = $request['cash'];
        $coins = $request['coins'];

        if($request->has('offsale'))
        {
            $cash = 0;
            $coins = 0;
        }
        
        if($request->has('free'))
        {
            $cash = -1;
            $coins = -1;
        }

        $update = $item->update([
            'name' => $request['title'],
            'desc' => $request['description'],
            'cash' => $cash,
            'coins' => $coins,
            'updated_real' => Carbon::now(),
        ]);

        return back()->with('success', 'Item successfully updated!');
    }

    public function edit_item(Request $request, Item $item)
    {
        $item = Item::find($item->id);

        if(!$item->exists())
        {
            abort(404);
        }

        if(auth()->user()->id != $item->owner->id)
            return abort(403);

        return view('market.edit', compact(['item']));
    }

    public function show_item(Request $request, Item $item)
    {
        if(!$item->exists) {
            abort(404);
        }

        $comments = Comment::where('target_id', '=', $item->id)->where('type', '=', '1')->where('scrubbed', '=', '0')->orderBy('created_at', 'DESC')->paginate('5');

        if($request->ajax() && $comments->count() > 0)
        {
            $view = view('components.load_item_comments', compact('comments'))->render();
            return response()->json(['html' => $view]);
        }

        $markets = $item->market()->paginate(5, '*', 'resellers');

        return view('market.item', compact(['item', 'comments', 'markets']));
    }

    public function add_comment(Request $request, Item $item)
    {
        if($item->exists)
        {
            abort(404);
        }

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->withInput()->with('error', 'Please wait '. env('FLOOD_GATE') . ' seconds before making another post.');
        }

        $this->validate($request, [
            'body' => ['required', 'min:3', 'max:280', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/#$&\t\n\r]+/i'],
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'text' => request('body'),
            'target_id' => $item->id,
        ]);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();
        return back()->with('success', 'Successfully posted comment!');
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

        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
             return back()->with('error', 'You\'re doing that too fast!');
        }

        $request->validate([
            'title' => 'required|min:3|max:64',
            'description' => 'max:2048',
            'image' => 'required|image|mimes:png|max:2048',
        ]);

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->extension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/storage/shirts',
        ]);

        $disk->putFileAs('', $request->image, $imageName);

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
            'collection_number' => $this->generateSerial(),
        ]);

        app('App\Http\Controllers\API\AvatarsController')->market($upload);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return redirect(route('market.item', $upload->id));
    }

    public function upload_pants(Request $request)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'image' => ['required', 'image', 'mimes:png', 'max:2048'],
            'title' => ['required', 'min:3', 'max:64'],
            'description' => ['max:2048'],
        ]);

        $realName = bin2hex(random_bytes(32));
        $imageName = $realName.'.'.request()->image->getClientOriginalExtension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => '/var/www/storage/pants',
        ]);

        $disk->putFileAs('', $request->image, $imageName);

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

        $grantItem = Inventory::create([
            'user_id' => Auth::user()->id,
            'item_id' => $upload->id,
            'type' => $upload->type,
            'collection_number' => $this->generateSerial(),
        ]);

        app('App\Http\Controllers\API\AvatarsController')->market($upload);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return redirect(route('market.item', $upload->id));
    }

    public function comment(Request $request, Item $item)
    {
        if(!auth()->user()->flood_gate || auth()->user()->flood_gate > (Carbon::now()->subSeconds(env('FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'body' => ['required', 'string', 'min:3', 'max:120'],
        ]);

        Comment::create([
            'user_id' => auth()->user()->id,
            'text' => $request['body'],
            'target_id' => $item->id,
        ]);

        $flood = auth()->user();
        $flood->flood_gate = Carbon::now();
        $flood->save();

        return back();
    }

    public function buy_item(Item $item, $type)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $user = auth()->user();

        // check ownership
        if($user->owns($item))
        {
            return back()->with('error', 'You already own this item!');
        }

        $release = Carbon::now();
        $release = $release->addDay();

        if($item->owner->membership())
        {
            $cash = $item->cash * env('PAID_TAX');
            $coins = $item->coins * env('PAID_TAX');
        } else {
            $cash = $item->cash * env('FREE_TAX');
            $coins = $item->coins * env('FREE_TAX');
        }

        if($item->stock() == 0)
        {
            return back()->with('error', 'This item is out of stock.');
        }

        // cash
        if($type == 1)
        {
            if($user->cash >= $item->cash && $item->cash != 0)
            {
                $user->revoke_currency($item->cash, $type);

                $logPurchase = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'source_id' => $item->id,
                    'source_user' => $item->owner->id,
                    'source_type' => '1',
                    'type' => '1',
                    'cash' => $item->cash,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $item->owner->id,
                    'source_id' => $item->id,
                    'source_user' => auth()->user()->id,
                    'source_type' => '1',
                    'type' => '2',
                    'cash' => $cash,
                    'release_at' => $release,
                ]);
                $grantItem = Inventory::create([
                    'user_id' =>auth()->user()->id,
                    'item_id' => $item->id,
                    'type' => $item->type,
                    'collection_number' => $this->generateSerial(),
                    'special' => $item->special,
                ]);

                $user->action_flood_gate = Carbon::now();
                $user->save();

                return back()->with('success', 'Successfully purchased ' . $item->name .'!');
            }
        }
        // coins
        elseif($type == 2) {
            if($user->coins >- $item->coins && $item->coins != 0)
            {
                $user->revoke_currency($item->coins, $type);

                $logPurchase = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'source_id' => $item->id,
                    'source_user' => $item->owner->id,
                    'source_type' => '1',
                    'type' => '1',
                    'coins' => $item->coins,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $item->owner->id,
                    'source_id' => $item->id,
                    'source_user' => auth()->user()->id,
                    'source_type' => '1',
                    'type' => '2',
                    'coins' => $coins,
                    'release_at' => $release,
                ]);
                $grantItem = Inventory::create([
                    'user_id' => auth()->user()->id,
                    'item_id' => $item->id,
                    'type' => $item->type,
                    'collection_number' => $this->generateSerial(),
                    'special' => $item->special,
                ]);

                $user->action_flood_gate = Carbon::now();
                $user->save();

                return back()->with('success', 'Successfully purchased ' . $item->name .'!');
            }
        }
        // free
        elseif($type == 3) {
            $logPurchase = Transaction::create([
                'user_id' => auth()->user()->id,
                'source_id' => $item->id,
                'source_user' => $item->owner->id,
                'source_type' => '1',
                'type' => '1',
            ]);
            $logSale = Transaction::create([
                'user_id' => $item->owner->id,
                'source_id' => $item->id,
                'source_user' => auth()->user()->id,
                'source_type' => '1',
                'type' => '2',
            ]);
            $grantItem = Inventory::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item->id,
                'type' => $item->type,
                'collection_number' => $this->generateSerial(),
                'special' => $item->special,
            ]);

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Successfully purchased ' . $item->name .'!');
        }
    }

    public function delete(Request $request, Item $item)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        $user = auth()->user();

        // check ownership
        if($user->owns($item) && $item->special == 0)
        {
            $get = Inventory::where('item_id', '=', $item->id)->where('user_id', '=', auth()->user()->id)->first();
            $get->delete();

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Deleted item from inventory!');
        } else {
            return back()->with('error', 'You don\'t own this item!');
        }
    }

    public function list(Request $request, Item $item)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'price' => ['required', 'numeric', 'min:1'],
        ]);

        $user = auth()->user();

        $getItem = Inventory::where('id', '=', request('serial'))->first();

        // check ownership
        if($user->owns($item) && $getItem != null && !$getItem->onsale())
        {
            ItemReseller::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item->id,
                'inventory_id' => request('serial'),
                'price' => request('price'),
            ]);

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Serial #'.$getItem->collection_number.' successfully listed on the market.');
        } else {
            return back()->with('error', 'There was an error trying to sell this item.');
        }
    }

    public function unlist(Request $request, Item $item)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'serial' => ['required'],
        ]);

        $user = auth()->user();

        $getItem = ItemReseller::where('id', '=', request('serial'))->first();

        // check ownership
        if($user->owns($item) && $getItem != null)
        {
            $item = ItemReseller::where([
                ['id', '=', request('serial')],
                ['user_id', '=', $user->id],
            ])->firstOrFail();

            $item->delete();

            $user->action_flood_gate = Carbon::now();
            $user->save();

            return back()->with('success', 'Serial #'.$getItem->inventory->collection_number.' successfully removed from the market.');
        } else {
            return back()->with('error', 'There was an error trying to take this item offsale.');
        }

    }

    public function buy_listing(Request $request, Item $item, ItemReseller $listing)
    {
        // action flood gate
        if(!auth()->user()->action_flood_gate || auth()->user()->action_flood_gate > (Carbon::now()->subSeconds(env('ACTION_FLOOD_GATE'))))
        {
            return back()->with('error', 'You\'re doing that too fast!');
        }

        request()->validate([
            'serial' => ['required'],
        ]);

        $user = auth()->user();

        $getItem = ItemReseller::where('id', '=', request('serial'))->first();

        if($getItem != null && (auth()->user()->id != $getItem->user_id))
        {
            if(auth()->user()->cash >= $getItem->price)
            {
                $release = Carbon::now();
                $release = $release->addDay();

                if($getItem->seller->membership())
                {
                    $cash = $getItem->price * env('PAID_TAX');
                } else {
                    $cash = $getItem->price * env('FREE_TAX');
                }

                $user->revoke_currency($getItem->price, 1);

                $logPurchase = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'source_id' => $item->id,
                    'source_user' => $getItem->seller->id,
                    'source_type' => '1',
                    'type' => '1',
                    'cash' => $getItem->price,
                ]);
                $logSale = Transaction::create([
                    'user_id' => $getItem->seller->id,
                    'source_id' => $item->id,
                    'source_user' => auth()->user()->id,
                    'source_type' => '1',
                    'type' => '2',
                    'cash' => $cash,
                    'release_at' => $release,
                ]);

                $logData = ItemData::create([
                    'item_id' => $item->id,
                    'price' => $getItem->price,
                ]);
                
                $inventory = $getItem->inventory;
                $inventory->user_id = auth()->user()->id;
                $inventory->save();

                $getItem->delete();

                $user->action_flood_gate = Carbon::now();
                $user->save();

                return back()->with('success', 'Successfully purchased Serial #'. $getItem->inventory->collection_number.' of ' . $item->name .'!');
            }
        } else {
            return back()->with('error', 'There was an error trying to buy this item.');
        }
    }

    private function generateSerial(): string
    {
        $randomHash = bin2hex(random_bytes(5));
        return $randomHash;
    }
}
