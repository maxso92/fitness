<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Item; // Подключите модель Item, если необходимо

class UpdateItemCountCache extends Command
{
    protected $signature = 'cache:update-item-count';

    protected $description = 'Update item count cache for today';

    public function handle()
    {
        $today = Carbon::today();
        $itemCount = Item::whereDate('created_at', $today)->count();

        Cache::put('item_count', $itemCount, $today->endOfDay());

        $this->info('Item count cache updated for today.');
    }
}
