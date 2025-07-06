<?php

namespace Tests\Unit;

use App\Models\LuckyLink;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use App\Services\LuckyService;

/**
 * Tests for checking LuckyService Class
 * php artisan test --filter=LuckyServiceTest
 * php artisan test --filter test_method_name
 */
class LuckyServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** test if winning defined correctly */
    public function test_is_winning_number()
    {
        $service = new LuckyService();

        $this->assertTrue($this->invokeMethod($service, 'isWinningNumber', [4]));
        $this->assertFalse($this->invokeMethod($service, 'isWinningNumber', [3]));
    }

    /** check basic number generation conditions */
    public function test_get_number_returns_integer_in_range(): void
    {
        $service = new LuckyService();
        $number = $this->invokeMethod($service, 'getNumber');

        $this->assertIsInt($number);
        $this->assertGreaterThanOrEqual(1, $number);
        $this->assertLessThanOrEqual(1000, $number);
    }

    /** is prepareWinRules logic works  */
    public function test_prepare_win_rules_filters_and_sorts_rules(): void
    {
        config()->set('lucky.win_rules', [
            ['min' => 300, 'win_percent' => 30],
            ['min' => 900, 'win_percent' => 70],
            ['min' => 600], // invalid  no win_percent
            ['win_percent' => 50], // invalid - no min
            ['min' => 600, 'win_percent' => 50],
        ]);

        $service = new LuckyService();

        $rules = $this->invokeMethod($service, 'prepareWinRules');

        $this->assertEquals([
            ['min' => 900, 'win_percent' => 70],
            ['min' => 600, 'win_percent' => 50],
            ['min' => 300, 'win_percent' => 30],
        ], $rules);
    }

    /** amount rule works */
    public function test_calculate_amount_applies_correct_rule(): void
    {
        $this->setCorrectWinRulesConfig();
        $service = new LuckyService();

        // number = 650 -> should apply 50%
        $amount = $this->invokeMethod($service, 'calculateAmount', [650]);

        $this->assertEquals('325.00', $amount);
    }

    /** highest amount rule works first */
    public function test_calculate_amount_applies_highest_matching_rule(): void
    {
        $this->setCorrectWinRulesConfig();
        $service = new LuckyService();

        // number = 950 -> should apply 70%
        $amount = $this->invokeMethod($service, 'calculateAmount', [950]);

        $this->assertEquals('665.00', $amount);
    }

    /** lowest amount rule works last */
    public function test_calculate_amount_applies_lowest_rule_if_no_others_match(): void
    {
        $this->setCorrectWinRulesConfig();
        $service = new LuckyService();

        // number = 10 -> should apply 10%
        $amount = $this->invokeMethod($service, 'calculateAmount', [10]);

        $this->assertEquals('1.00', $amount);
    }

    /** nothing works if rules empty */
    public function test_calculate_amount_returns_zero_if_no_rules(): void
    {
        config()->set('lucky.win_rules', []);
        $service = new LuckyService();

        $amount = $this->invokeMethod($service, 'calculateAmount', [500]);

        $this->assertEquals('0.00', $amount);
    }

    /** Tests if Cache::remember is called with correct arguments */
    public function test_get_by_token_caches_result(): void
    {
        $token = fake()->uuid;
        $link = LuckyLink::factory()->create(['token' => $token]);

        Cache::shouldReceive('remember')
            ->once()
            ->with("lucky_link_" . $token, \Mockery::any(), \Closure::class)
            ->andReturn($link);

        $service = new LuckyService();
        $result = $service->getByToken($token);

        $this->assertEquals($link->id, $result->id);
    }

    /** Tests if the link is stored in and retrieved from the real cache */
    public function test_get_by_token_caches_and_returns_lucky_link(): void
    {
        config(['cache.default' => 'array']);

        $token = fake()->uuid;
        $link = LuckyLink::factory()->create(['token' => $token]);

        $service = new LuckyService();

        // get and write cache
        $result1 = $service->getByToken($token);

        // get from cache
        $result2 = $service->getByToken($token);

        $this->assertEquals($link->id, $result1->id);
        $this->assertEquals($link->id, $result2->id);

        // check key
        $this->assertTrue(Cache::has('lucky_link_' . $token));
    }

    /** For testing private methods */
    protected function invokeMethod($object, string $methodName, array $params = [])
    {
        $method = new \ReflectionMethod(get_class($object), $methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $params);
    }

    protected function setCorrectWinRulesConfig():void
    {
        config()->set('lucky.win_rules', [
            ['min' => 900, 'win_percent' => 70],
            ['min' => 600, 'win_percent' => 50],
            ['min' => 300, 'win_percent' => 30],
            ['min' => 0,   'win_percent' => 10],
        ]);
    }
}
