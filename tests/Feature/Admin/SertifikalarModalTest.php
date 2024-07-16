<?php

namespace Tests\Feature\Admin;

use App\Models\superadmin\KursModal;
use App\Models\User;
use App\Models\superadmin\KurumModal;
use App\Models\superadmin\OgrencilerModal;
use App\Models\superadmin\SertifikalarModal;
use Illuminate\Support\Str;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SertifikalarModalTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private KurumModal $kurum;
    private OgrencilerModal $ogrenci;
    private KursModal $kurs;
    private SertifikalarModal $sertifika;

    protected function setUp(): void
    {
        parent::setUp();

          $this->kurum = KurumModal::factory()->create([
            'kurumAdi' => 'Test Kurum',
            'kurumKodu' => '123456',
            'kullaniciAdi' => 'test',
            'sifre' => '123456',
            'lisansSayisi' => 100,
          ]);

          $this->user = User::factory()->create(
            [
                'name' => 'Test User',
                'email' => 'q@q.com',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
                'userStatus' => 'active',
                'remember_token' => Str::random(10),
                'userInstitution' => $this->kurum->id,
                'userType' => 'admin',
            ]
          );

          $this->ogrenci = OgrencilerModal::factory()->create([
            'ogrenciAdi' => 'Test User',
            'ogrenciSoyadi' => 'Test User',
            'tcKimlikNo' => '37517084476',
            'kurumId' => $this->kurum->id,
        ]);

        $this->kurs = KursModal::factory()->create([
            'kursAdi' => 'Test Kurs',
            'kursKurumId' => $this->kurum->id,
            'aciklama' => 'Test Açıklama',
            'baslangicTarihi' => '2021-01-01',
            'bitisTarihi' => '2021-01-01',
            'sertifikaAdi' => 'Test Sertifika',
            'baslik' => 'Test Başlık',
            'tur' => 'Katılım Sertifikası',
            'sertifikaGecerlilikTarihi' => '2021-01-01',
            'sablonDosyasi' => 'Test Dosya',
        ]);

        $this->sertifika = SertifikalarModal::factory()->create([
            'kursId' => $this->kurs->id,
            'ogrenciId' => $this->ogrenci->id,
            'sertifikaNo' => '123456',
            'kurumId' => $this->kurum->id,
        ]);

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_index_page()
    {
        $response = $this->actingAs($this->user)->get('/admin/sertifikalar');

        $response->assertStatus(200);
        $response->assertSee('Tüm Sertifika listesi');
    }
    public function test_user_can_see_add_page(){
        $response = $this->actingAs($this->user)->get('/admin/sertifikalar/add');

        $response->assertStatus(200);
        $response->assertSee('Sertifika Ekle');
    }
    public function test_user_can_see_edit_page(){
        $response = $this->actingAs($this->user)->get('/admin/sertifikalar/edit/'.$this->sertifika->id);

        $response->assertStatus(200);
        $response->assertSee('Sertifika Düzenleme');
    }
    public function test_sertifika_edit_has_correct_value(){

        $response = $this->actingAs($this->user)->get('/admin/sertifikalar/edit/'.$this->sertifika->id);
        $response->assertSee($this->sertifika->ogrenciId);
    }
    public function test_sertifika_ekleme(){
        $sertifika = [
            'kursId' => $this->kurs->id,
            'ogrenciId' => $this->ogrenci->id,
            'kurumId' => $this->kurum->id,
        ];

        $response = $this->actingAs($this->user)->post('/admin/sertifikalar/add', $sertifika);
        $this->assertDatabaseHas('sertifikalar_modals', $sertifika);
    }
    public function test_sertifika_duzenleme(){
        $sertifika = [
            'kursId' => $this->kurs->id,
            'ogrenciId' => $this->ogrenci->id,
            'kurumId' => $this->kurum->id,
        ];

        $response = $this->actingAs($this->user)->post('/admin/sertifikalar/edit/'.$this->sertifika->id, $sertifika);
        $this->assertDatabaseHas('sertifikalar_modals', $sertifika);
    }
    public function test_sertifika_silme(){
        $response = $this->actingAs($this->user)->get('/admin/sertifikalar/delete/'.$this->sertifika->id);
        $this->assertDeleted('sertifikalar_modals', ['id' => $this->sertifika->id]);
    }

}
