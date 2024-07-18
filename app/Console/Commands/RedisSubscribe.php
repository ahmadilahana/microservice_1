<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "Redis Subscribe Started";
        try {
            Redis::subscribe(['updated_user'], function ($message) {
                $user = json_decode($message);
                $data = User::where("id", $user->id)->first();
                if ($data) {
                    $data->update([
                        'name' => $user->name,
                        'email' => $user->email,
                    ]);
                }else {
                    User::create([
                        "id" => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ]);
                }
            });
        } catch (ConnectionException $e) {
            echo "koneksi redis error ".$e->getMessage();
        }
    }
}
