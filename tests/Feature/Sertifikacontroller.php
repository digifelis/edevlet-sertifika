<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Sertifikacontroller extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_sertifika_index()
    {
        $response = $this->get('/superadmin/sertifikalar');
        $response->assertStatus(200);
    }

    public function test_sertifika_add()
    {
        $response = $this->get('/superadmin/sertifikalar/add');
        $response->assertStatus(200);
    }

    public function test_sertifika_edit()
    {
        $response = $this->get('/superadmin/sertifikalar/edit/1');
        $response->assertStatus(200);
    }

    public function test_sertifika_delete()
    {
        $response = $this->get('/superadmin/sertifikalar/delete/1');
        $response->assertStatus(200);
    }

}
