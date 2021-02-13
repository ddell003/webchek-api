<?php


namespace Database\Factories;


use App\Models\Account;
use App\Models\App;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = App::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $account = Account::factory()->create();
        $user = User::factory()->create(['account_id'=>$account->id]);
        return [
            'account_id' =>$account->id,
            'name' => $this->faker->name,
            'status' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'created_by'=> $user->id,
            'updated_by'=>$user->id,

        ];
    }
}
