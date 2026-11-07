<?php

namespace Tests\Feature\Finance;

use App\Models\Finance\PettyCashTransaction;
use App\Models\User;
use Database\Seeders\System\PermissionSeeder;
use Database\Seeders\System\RoleSeeder;
use Database\Seeders\System\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PettyCashTest extends TestCase
{
    use RefreshDatabase;

    private const INTERNAL_HOST = 'http://app.meditech.local';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $this->seed(RolePermissionSeeder::class);
    }

    private function createUserWithRole(string $email, string $roleName): User
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test ' . $roleName,
            'email' => $email,
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ], 'user_id');

        $roleId = (int) DB::table('roles')->where('role_name', $roleName)->value('role_id');

        DB::table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
            'assigned_by' => null,
            'assigned_at' => now(),
        ]);

        return User::findOrFail($userId);
    }

    private function createPettyCash(array $overrides = []): PettyCashTransaction
    {
        $userId = DB::table('users')->value('user_id') ?? 1;

        return PettyCashTransaction::create(array_merge([
            'transaction_number' => 'PC-2026-001',
            'transaction_date' => now()->subDay(),
            'transaction_type' => 'Pengeluaran',
            'category' => 'Operasional kantor',
            'description' => 'Pembelian alat tulis',
            'amount' => 450000,
            'payment_method' => 'Transfer bank',
            'bank_account_id' => null,
            'proof_file_path' => 'demo/payment-proofs/nota-atk.svg',
            'recorded_by' => $userId,
            'status' => 'Posted',
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_guest_cannot_access_petty_cash(): void
    {
        $this->get(self::INTERNAL_HOST . '/petty-cash')->assertRedirect(self::INTERNAL_HOST . '/login');
    }

    public function test_user_without_permission_gets_403(): void
    {
        $user = $this->createUserWithRole('sales@test.local', 'Sales');

        $this->actingAs($user)
            ->get(self::INTERNAL_HOST . '/petty-cash')
            ->assertForbidden();
    }

    public function test_finance_role_can_access_petty_cash(): void
    {
        $user = $this->createUserWithRole('finance@test.local', 'Finance');

        $this->createPettyCash();

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/petty-cash');

        $response->assertOk();
        $response->assertSee('Petty Cash');
        $response->assertSee('PC-2026-001');
    }

    public function test_filter_by_type_works(): void
    {
        $user = $this->createUserWithRole('finance2@test.local', 'Finance');

        $this->createPettyCash(['transaction_number' => 'PC-2026-001', 'transaction_type' => 'Pengeluaran']);
        $this->createPettyCash(['transaction_number' => 'PC-2026-002', 'transaction_type' => 'Pemasukan', 'amount' => 250000]);

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/petty-cash?type=Pemasukan');

        $response->assertOk();
        $response->assertSee('PC-2026-002');
        $response->assertDontSee('PC-2026-001');
    }

    public function test_trash_page_accessible(): void
    {
        $user = $this->createUserWithRole('finance3@test.local', 'Finance');

        $this->createPettyCash(['deleted_at' => now(), 'deleted_by' => DB::table('users')->value('user_id'), 'deletion_reason' => 'Test']);

        $response = $this->actingAs($user)->get(self::INTERNAL_HOST . '/petty-cash/trash');

        $response->assertOk();
        $response->assertSee('Sampah');
    }
}