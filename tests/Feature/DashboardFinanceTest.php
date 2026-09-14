<?php

namespace Tests\Feature;

use App\Models\{Order, Pemasukan, Pengeluaran, PengeluaranBerulang, TransactionCategory, User};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardFinanceTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'status' => 'Pending',
            'payment_status' => 'DP',
            'nama_pembeli' => 'Test Pembeli',
            'alamat' => 'Jl. Contoh',
            'no_hp' => '08' . random_int(1000000000, 1999999999),
            'order_via' => 'WhatsApp',
            'tgl_order' => now()->format('Y-m-d'),
            'tgl_kirim' => now()->addDays(2)->format('Y-m-d'),
            'title' => 'Judul',
            'keterangan' => 'Keterangan',
            'total_qty' => 1,
            'total_harga_jual' => 100000,
            'jumlah_dibayar' => 0,
        ], $overrides));
    }

    private function income(int $amount, string $date): void
    {
        $category = TransactionCategory::firstOrCreate(['nama_kategori' => 'Test income', 'jenis' => 'pemasukan']);
        Pemasukan::create(['kategori_id' => $category->id, 'sumber' => 'Synthetic', 'jumlah' => $amount, 'tanggal' => $date]);
    }

    private function expense(int $amount, string $date, string $status = 'terkonfirmasi', ?int $template = null): void
    {
        $category = TransactionCategory::firstOrCreate(['nama_kategori' => 'Test expense', 'jenis' => 'pengeluaran']);
        Pengeluaran::create(['kategori_id' => $category->id, 'jumlah' => $amount, 'tanggal' => $date,
            'status' => $status, 'pengeluaran_berulang_id' => $template]);
    }

    private function dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        return $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_monthly_finance_counts_paid_amount_and_only_confirmed_expenses(): void
    {
        $this->travelTo(now()->setDate(2026, 1, 15));
        $this->makeOrder(['jumlah_dibayar' => 40000, 'total_harga_jual' => 100000]);
        $this->makeOrder(['tgl_order' => '2025-01-15', 'jumlah_dibayar' => 900000]);
        $this->income(10000, '2026-01-01');
        $this->income(900000, '2025-01-15');
        $this->income(900000, '2025-12-31');
        $this->expense(70000, '2026-01-15');
        $this->expense(900000, '2026-01-15', 'draft');
        $this->expense(900000, '2025-01-15');
        $this->dashboard()->assertViewHas('totalPemasukanBulanIni', 50000)
            ->assertViewHas('totalPengeluaranBulanIni', 70000)
            ->assertViewHas('labaBersihBulanIni', -20000);
    }

    public function test_month_end_trends_include_six_distinct_months_across_year_boundary(): void
    {
        $this->travelTo(now()->setDate(2026, 3, 31));
        foreach (['2025-10-15', '2025-11-15', '2025-12-15', '2026-01-15', '2026-02-15', '2026-03-15'] as $i => $date) {
            $this->income(($i + 1) * 1000, $date);
            $this->expense(($i + 1) * 100, $date);
            $this->makeOrder(['tgl_order' => $date, 'total_harga_jual' => ($i + 1) * 10000]);
        }
        $this->dashboard()->assertViewHas('keuanganTrenPemasukan', [1000, 2000, 3000, 4000, 5000, 6000])
            ->assertViewHas('keuanganTrenPengeluaran', [100, 200, 300, 400, 500, 600])
            ->assertViewHas('trenData', [10000, 20000, 30000, 40000, 50000, 60000]);
    }

    public function test_recurring_budget_ignores_drafts_and_inactive_templates(): void
    {
        $this->travelTo(now()->setDate(2026, 1, 15));
        $category = TransactionCategory::create(['nama_kategori' => 'Recurring test', 'jenis' => 'pengeluaran']);
        foreach ([['Missing', true], ['Draft', true], ['Over', true], ['Exact', true], ['Inactive', false]] as [$name, $active]) {
            $template = PengeluaranBerulang::create(['kategori_id' => $category->id, 'nama' => $name,
                'jumlah_estimasi' => 1000, 'tanggal_jatuh_tempo' => 10, 'aktif' => $active]);
            if ($name === 'Missing') {
                $this->expense(2000, '2025-12-15', 'terkonfirmasi', $template->id);
            } elseif ($name === 'Draft') {
                $this->expense(5000, '2026-01-15', 'draft', $template->id);
            } else {
                $this->expense($name === 'Exact' ? 1000 : 2000, '2026-01-15', 'terkonfirmasi', $template->id);
            }
        }
        $this->dashboard()->assertViewHas('pengeluaranBerulangPendingCount', 1)
            ->assertViewHas('pengeluaranBerulangOverBudgetCount', 1);
    }
}
