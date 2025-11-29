<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BarcodeQuantityTest extends TestCase
{
    use RefreshDatabase; // Commented out to avoid wiping existing data if not using in-memory DB

    /**
     * Test generating multiple barcodes with quantity.
     *
     * @return void
     */
    public function test_can_generate_multiple_barcodes()
    {
        // Create a user to act as with 2FA enabled
        $user = User::factory()->create([
            'google2fa_secret' => 'SECRET',
        ]);

        // Create a dummy product
        $barang = Laporan::factory()->create([
            'kode_barang' => 'TEST-QTY-001',
            'nama_barang' => 'Test Product Quantity',
            'jumlah' => 10,
            'jenis_laporan' => 'masuk'
        ]);

        $quantity = 3;

        $response = $this->actingAs($user)
            ->withSession(['2fa_verified' => true])
            ->post(route('barcode.generate'), [
                'kode_barang' => $barang->kode_barang,
                'jenis_barcode' => 'keduanya',
                'quantity' => $quantity,
            ]);

        $response->assertStatus(200);
        
        // Assert view has the barcodes variable
        $response->assertViewHas('barcodes');
        
        // Get the barcodes from the view
        $barcodes = $response->viewData('barcodes');
        
        // Assert correct number of barcodes generated
        $this->assertCount($quantity, $barcodes);
        
        // Assert the codes are correct
        $this->assertEquals($barang->kode_barang . '-1', $barcodes[0]['code']);
        $this->assertEquals($barang->kode_barang . '-2', $barcodes[1]['code']);
        $this->assertEquals($barang->kode_barang . '-3', $barcodes[2]['code']);
        
        // Assert the HTML contains the generated codes
        $response->assertSee($barang->kode_barang . '-1');
        $response->assertSee($barang->kode_barang . '-2');
        $response->assertSee($barang->kode_barang . '-3');
    }
}
