<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;

class AnggotaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_anggota_routes(): void
    {
        $this->get('/anggota')->assertRedirect('/login');
        $this->get('/anggota/create')->assertRedirect('/login');
        $this->post('/anggota', [])->assertRedirect('/login');
        $this->get('/anggota/search')->assertRedirect('/login');
        $this->get('/anggota/export')->assertRedirect('/login');
    }

    public function test_index_page_displays_statistics_and_list(): void
    {
        Anggota::create([
            'kode_anggota' => 'AGT-2026-001',
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Aktif'
        ]);

        $response = $this->actingAs($this->user)->get('/anggota');
        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertSee('AGT-2026-001');
    }

    public function test_create_page_displays_auto_generated_code(): void
    {
        $response = $this->actingAs($this->user)->get('/anggota/create');
        $response->assertOk();
        $response->assertSee('AGT-' . date('Y') . '-001');
    }

    public function test_store_anggota_creates_record_in_database(): void
    {
        $data = [
            'kode_anggota' => 'AGT-' . date('Y') . '-001',
            'nama' => 'John Doe Testing',
            'email' => 'john.test@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Testing No. 1, Jakarta',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => date('Y-m-d'),
            'status' => 'Aktif'
        ];

        $response = $this->actingAs($this->user)->post('/anggota', $data);

        $response->assertRedirect('/anggota');
        $response->assertSessionHas('success', 'Anggota berhasil ditambahkan!');

        $this->assertDatabaseHas('anggota', [
            'nama' => 'John Doe Testing',
            'email' => 'john.test@example.com'
        ]);
    }

    public function test_store_validation_errors(): void
    {
        $response = $this->actingAs($this->user)->post('/anggota', [
            'email' => 'bukan-email',
            'telepon' => '123',
            'tanggal_lahir' => date('Y-m-d', strtotime('+1 day')),
        ]);

        $response->assertSessionHasErrors([
            'kode_anggota',
            'nama',
            'email',
            'telepon',
            'alamat',
            'tanggal_lahir',
            'jenis_kelamin',
            'tanggal_daftar',
            'status',
        ]);
    }

    public function test_edit_page_displays_anggota_details(): void
    {
        $anggota = Anggota::create([
            'kode_anggota' => 'AGT-2026-999',
            'nama' => 'Test Edit',
            'email' => 'testedit@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Edit',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Aktif'
        ]);

        $response = $this->actingAs($this->user)->get("/anggota/{$anggota->id}/edit");
        $response->assertOk();
        $response->assertSee($anggota->nama);
    }

    public function test_update_anggota_updates_record(): void
    {
        $anggota = Anggota::create([
            'kode_anggota' => 'AGT-2026-998',
            'nama' => 'Old Name',
            'email' => 'old@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Edit',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Aktif'
        ]);

        $data = [
            'kode_anggota' => $anggota->kode_anggota,
            'nama' => 'New Name',
            'email' => 'new@example.com',
            'telepon' => '081299999999',
            'alamat' => 'Jl. Baru No. 2',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Perempuan',
            'pekerjaan' => 'Pegawai',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Nonaktif'
        ];

        $response = $this->actingAs($this->user)->put("/anggota/{$anggota->id}", $data);

        $response->assertRedirect("/anggota/{$anggota->id}");
        $response->assertSessionHas('success', 'Data anggota berhasil diupdate!');

        $this->assertDatabaseHas('anggota', [
            'id' => $anggota->id,
            'nama' => 'New Name',
            'email' => 'new@example.com'
        ]);
    }

    public function test_destroy_anggota_removes_from_database(): void
    {
        $anggota = Anggota::create([
            'kode_anggota' => 'AGT-2026-997',
            'nama' => 'Test Delete',
            'email' => 'testdelete@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Delete',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Aktif'
        ]);

        $response = $this->actingAs($this->user)->delete("/anggota/{$anggota->id}");

        $response->assertRedirect('/anggota');
        $this->assertDatabaseMissing('anggota', ['id' => $anggota->id]);
    }

    public function test_search_anggota_filters_records(): void
    {
        Anggota::create([
            'kode_anggota' => 'AGT-2026-996',
            'nama' => 'Budi Santoso',
            'email' => 'budi.s@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Jl. Budi',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Aktif'
        ]);

        Anggota::create([
            'kode_anggota' => 'AGT-2026-995',
            'nama' => 'Siti Aminah',
            'email' => 'siti.a@example.com',
            'telepon' => '081234567891',
            'alamat' => 'Jl. Siti',
            'tanggal_lahir' => '1998-08-20',
            'jenis_kelamin' => 'Perempuan',
            'pekerjaan' => 'Pegawai',
            'tanggal_daftar' => '2026-07-06',
            'status' => 'Nonaktif'
        ]);

        $response = $this->actingAs($this->user)->get('/anggota/search?keyword=Budi');
        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Aminah');

        $responseJk = $this->actingAs($this->user)->get('/anggota/search?jenis_kelamin=Perempuan');
        $responseJk->assertOk();
        $responseJk->assertSee('Siti Aminah');
        $responseJk->assertDontSee('Budi Santoso');
    }

    public function test_export_anggota_downloads_excel(): void
    {
        Excel::fake();

        $response = $this->actingAs($this->user)->get('/anggota/export');
        $response->assertStatus(200);

        Excel::assertDownloaded('anggota_' . date('Y-m-d') . '_' . date('His') . '.xlsx');
    }
}
