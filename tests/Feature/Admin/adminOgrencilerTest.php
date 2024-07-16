<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\superadmin\KurumModal;
use App\Models\superadmin\OgrencilerModal;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class adminOgrenciler extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private KurumModal $kurum;
    private OgrencilerModal $ogrenci;

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

    }



    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_listeleme_sayfası()
    {

      //  $kurumlar = KurumModal::factory(1)->create();
          
        $response = $this->actingAs($this->user)->get('/admin/ogrenciler');

        $response->assertStatus(200);
        $response->assertSee('Tüm Öğrenci listesi');


    }

    public function test_ekleme_sayfası()
    {

      //  $kurumlar = KurumModal::factory(1)->create();
        $user = User::factory()->create();
        $response = $this->actingAs($this->user)->get('/admin/ogrenciler/add');
        $response->assertStatus(200);
        $response->assertSee('Öğrenci Ekle');
    }

    public function test_ogrenci_ekleme()
    {
        $ogrenci = [
          'ogrenciAdi' => 'Test User',
          'ogrenciSoyadi' => 'Test User',
          'tcKimlikNo' => '37517084476',
          'kurumId' => $this->kurum->id,
      ];

        $response = $this->actingAs($this->user)->post('/admin/ogrenciler/add', $ogrenci);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/ogrenciler');
     //   $response->assertSee('Öğrenci başarıyla eklendi.');

        $this->assertDatabaseHas('ogrenciler_modals', $ogrenci);
    }


    public function test_ogrenci_edit_has_correct_value(){
        $response = $this->actingAs($this->user)->get('/admin/ogrenciler/edit/'.$this->ogrenci->id);
        $response->assertStatus(200);
        $response->assertSee('Öğrenci Düzenle');
        $response->assertSee($this->ogrenci->ogrenciAdi);
        $response->assertSee($this->ogrenci->ogrenciSoyadi);
        $response->assertSee($this->ogrenci->tcKimlikNo);
    }

    public function test_ogrenci_updated(){
        $ogrenci = [
            'ogrenciAdi' => 'Test User',
            'ogrenciSoyadi' => 'Test User',
            'tcKimlikNo' => '37517084476',
            'kurumId' => $this->kurum->id,
        ];
        $response = $this->actingAs($this->user)->post('/admin/ogrenciler/edit/'.$this->ogrenci->id, $ogrenci);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/ogrenciler');
        $this->assertDatabaseHas('ogrenciler_modals', $ogrenci);
    }

    public function test_ogrenci_deleted(){
        $response = $this->actingAs($this->user)->get('/admin/ogrenciler/delete/'.$this->ogrenci->id);
        $response->assertStatus(302);
        $response->assertRedirect('/admin/ogrenciler');
        $this->assertDeleted('ogrenciler_modals', ['id' => $this->ogrenci->id]);
    }


}
