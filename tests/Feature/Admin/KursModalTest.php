<?php

namespace Tests\Feature\Admin;

use App\Models\superadmin\KursModal;
use App\Models\User;
use App\Models\superadmin\KurumModal;
use App\Models\superadmin\OgrencilerModal;
use Illuminate\Support\Str;


use Illuminate\Http\UploadedFile;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class KursModalTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private KurumModal $kurum;
    private OgrencilerModal $ogrenci;
    private KursModal $kurs;

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

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_see_index_page()
    {
        $response = $this->actingAs($this->user)->get('/admin/kurslar');

        $response->assertStatus(200);
    }

    public function test_user_can_see_add_page()
    {
        $response = $this->actingAs($this->user)->get('/admin/kurslar/add');
        $response->assertStatus(200);
    }

    public function test_user_can_see_edit_page()
    {
        $response = $this->actingAs($this->user)->get('/admin/kurslar/edit/'.$this->kurs->id);
        $response->assertStatus(200);
    }

    public function test_kurs_edit_has_correct_value(){

        $response = $this->actingAs($this->user)->get('/admin/kurslar/edit/'.$this->kurs->id);
        $response->assertSee($this->kurs->kursAdi);
        $response->assertSee($this->kurs->aciklama);
        $response->assertSee($this->kurs->baslangicTarihi);
        $response->assertSee($this->kurs->bitisTarihi);
        $response->assertSee($this->kurs->sertifikaAdi);
        $response->assertSee($this->kurs->baslik);
     //   $response->assertSee($this->kurs->tur);
        $response->assertSee($this->kurs->sertifikaGecerlilikTarihi);
        $response->assertSee($this->kurs->sablonDosyasi);

    }

    public function test_kurs_can_updated(){
            // create a fake file to upload
            $file = UploadedFile::fake()->create('test.docx');
            $kurs = [
                'kursAdi' => 'Test Kurs',
                'kursKurumId' => $this->kurum->id,
                'aciklama' => 'Test Açıklama',
                'baslangicTarihi' => '2021-01-01',
                'bitisTarihi' => '2021-01-01',
                'sertifikaAdi' => 'Test Sertifika',
                'baslik' => 'Test Başlık',
                'tur' => 'Katılım Sertifikası',
                'sertifikaGecerlilikTarihi' => '2021-01-01',
              //  'sablonDosyasi' => $file
            ];
    
            $response = $this->actingAs($this->user)->post('/admin/kurslar/edit/'.$this->kurs->id, $kurs);
            $this->assertDatabaseHas('kurs_modals', $kurs);

    }

    public function test_kurs_can_deleted(){
        $response = $this->actingAs($this->user)->get('/admin/kurslar/delete/'.$this->kurs->id);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/kurslar');
        $response->assertSessionHas('message', 'Kurs silindi.');
        $this->assertDeleted('kurs_modals', ['id' => $this->kurs->id]);
    }

}
