<?php

namespace App\Http\Controllers;

use App\Goods;
use App\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Log;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function purchaseItem(Request $request, $idGoods, $idUser) {
        $goods = Goods::find($idGoods);

        if (!Redis::HEXISTS('goods_' . $goods->id, 'stock')) {
            Redis::HSET('goods_' . $goods->id, 'stock', $goods->stock);
            Redis::HSET('goods_' . $goods->id, 'sold', 0);
        }

        $qty = $request->input('quantity');
        $value = Redis::eval($this->luaScript(), 1, 'goods_' . $goods->id, $qty);

        if ($value != 0) {
            $goods->stock -= $qty;
            $goods->save();
        } else {
            return response()->json(['response_code' => 99, 'message' => 'Stock Unavailability']);
        }

        return response()->json(['response_code' => 00, 'message' => 'success purchase']);
    }

    protected function luaScript()
    {
        return <<<'LUA'
local qty = tonumber(ARGV[1])

local stock = tonumber(redis.call('HGET', KEYS[1], 'stock'))
local sold = tonumber(redis.call('HGET', KEYS[1], 'sold'))

if sold == stock or stock == 0 then
        return 0
end
if sold + qty <= stock then
        redis.call('HINCRBY', KEYS[1], 'sold', qty)
        return qty
end

return 0

LUA;
    }
}
