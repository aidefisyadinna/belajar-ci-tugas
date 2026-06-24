<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel; 
use App\Models\TransactionDetailModel; 
use App\Models\CartModel; 

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client; 
    protected $apiKey;
    protected $transaction; 
    protected $transaction_detail; 
    protected $cartModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->client = new \GuzzleHttp\Client(); 
        $this->apiKey = env('COST_KEY');
        $this->transaction = new TransactionModel(); 
        $this->transaction_detail = new TransactionDetailModel(); 
        $this->cartModel = new CartModel();
    }

    public function index()
    {
    $userId = session('id');
    if ($userId) {
        $dbItems = $this->cartModel->getCartByUser($userId);
        $this->cart->destroy();
        foreach ($dbItems as $row) {
            $this->cart->insert([
                'id'      => $row['product_id'],
                'qty'     => $row['qty'],
                'price'   => $row['harga'],
                'name'    => $row['nama'],
                'options' => ['foto' => $row['foto']],
            ]);
        }
    }

    $data = [
        'items' => $this->cart->contents(),
        'total' => $this->cart->total()
    ];

    return view('v_keranjang', $data);
}

public function cart_add()
{
	$this->cart->insert([
	    'id'      => $this->request->getPost('id'),
	    'qty'     => 1,
	    'price'   => $this->request->getPost('harga'),
	    'name'    => $this->request->getPost('nama'),
	    'options' => [
	        'foto' => $this->request->getPost('foto')
	    ]
	]);

	$userId = session('id');
	if ($userId) {
	    $productId = $this->request->getPost('id');
	    $this->cartModel->addOrUpdateItem($userId, $productId, 1);
	}
	
	session()->setFlashdata(
	    'success',
	    'Produk berhasil ditambahkan ke keranjang. 
	    <a href="' . base_url('keranjang') . '">Lihat</a>'
	);
	
	return redirect()->to(base_url('/'));
} 
public function cart_edit()
{
    $i = 1;
    foreach ($this->cart->contents() as $item) {
        $qty = (int) $this->request->getPost('qty' . $i++);
        if ($qty < 1) {
            session()->setFlashdata('error', 'Qty harus minimal 1');
            return redirect()->to(base_url('keranjang'));
        }

        $this->cart->update([
            'rowid' => $item['rowid'],
            'qty'   => $qty
        ]);

        $userId = session('id');
        if ($userId) {
            $this->cartModel->updateQty($userId, $item['id'], $qty);
        }
    }

    session()->setFlashdata(
        'success',
        'Keranjang berhasil diperbarui'
    );

    return redirect()->to(base_url('keranjang'));
}
public function checkout() 
{   $data['items'] = $this->cart->contents(); 
    $data['total'] = $this->cart->total(); 
    return view('v_checkout', $data); 
} 

public function buy()
{
    $username = session()->get('username');
    $alamat = $this->request->getPost('alamat');
    $ongkir = $this->request->getPost('ongkir') ?? 0;
    $cart = $this->cart->contents();

    if (empty($cart)) {
        return redirect()->to('keranjang')->with('error', 'Keranjang kosong!');
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['subtotal'];
    }
    $total += $ongkir;

    $transaction_id = $this->transaction->insert([
        'username'    => $username,
        'total_harga' => $total,
        'alamat'      => $alamat,
        'ongkir'      => $ongkir,
        'status'      => 0,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ]);

    foreach ($cart as $item) {
        $this->transaction_detail->insert([
            'transaction_id'  => $transaction_id,
            'product_id'      => $item['id'],
            'jumlah'          => $item['qty'],
            'subtotal_harga'  => $item['subtotal'],
        ]);
    }

    $this->cart->destroy();

    $userId = session('id');
    if ($userId) {
        $this->cartModel->clearCart($userId);
    }

    return redirect()->to('profile')->with('success', 'Pesanan berhasil dibuat!');
}

public function updateStatus($id)
{
    $status = $this->request->getPost('status');
    if ($this->transaction->updateStatus($id, $status)) {
        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    } else {
        return redirect()->back()->with('error', 'Gagal memperbarui status transaksi.');
    }
}

// public function getLocation() 
// { 
//         //keyword pencarian yang dikirimkan dari halaman checkout 
//     dd($this->apiKey);
//     $search = $this->request->getGet('search'); 
//     $response = $this->client->request( 
//         'GET',  
//         'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='. $search .'&limit=50', 
//         [ 
//             'headers' => [ 
//                 'accept' => 'application/json', 
//                 'key' => $this->apiKey, 
//             ], 
//         ] 
//     ); 
 
//     $body = json_decode($response->getBody(), true);  
//     return $this->response->setJSON($body['data']); 
// } 

// public function getLocation()
// {
//     $search = $this->request->getGet('search');

//     $response = $this->client->request(
//         'GET',
//         'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=5',
//         [
//             'headers' => [
//                 'accept' => 'application/json',
//                 'key' => $this->apiKey,
//             ],
//         ]
//     );

//     $body = json_decode($response->getBody(), true);

//     dd($body);
// }

public function getLocation()
{
    try {

        $search = $this->request->getGet('search');

        $response = $this->client->request(
            'GET',
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50',
            [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true);

        return $this->response->setJSON($body['data']);

    } catch (\Exception $e) {

        dd($e->getMessage());

    }
}

public function getCost() 
{  
        //ID lokasi yang dikirimkan dari halaman checkout 
    $destination = $this->request->getGet('destination'); 
 
        //parameter daerah asal pengiriman, berat produk, dan kurir dibuat statis 
    //valuenya => 64999 : PEDURUNGAN TENGAH , 1000 gram, dan JNE 
    $response = $this->client->request( 
        'POST',  
        'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', 
        [ 
            'multipart' => [ 
                [ 
                    'name' => 'origin', 
                    'contents' => '64999' 
                ], 
                [ 
                    'name' => 'destination', 
                    'contents' => $destination 
                ], 
                [ 
                    'name' => 'weight', 
                    'contents' => '1000' 
                ], 
                [ 
                    'name' => 'courier', 
                    'contents' => 'jne' 
                ] 
            ], 
            'headers' => [ 
                'accept' => 'application/json', 
                'key' => $this->apiKey, 
            ], 
        ] 
    ); 
 
    $body = json_decode($response->getBody(), true);  
    return $this->response->setJSON($body['data']); 
} 

public function uploadBukti()
{
    $id   = $this->request->getPost('id_pembelian');
    $file = $this->request->getFile('bukti');

    if ($file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move('uploads/bukti/', $newName);
        $this->transaction->update($id, [
            'bukti_pembayaran' => $newName,
            'status'           => 1,
            'updated_at'       => date('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }
    return redirect()->back()->with('error', 'Upload bukti gagal.');
}

public function cart_delete($rowid)
{
    $item = $this->cart->getItem($rowid);
    $productId = $item ? $item['id'] : null;

    $this->cart->remove($rowid);

    $userId = session('id');
    if ($userId && $productId) {
        $this->cartModel->removeItem($userId, $productId);
    }

    session()->setFlashdata(
        'success',
        'Produk berhasil dihapus dari keranjang'
    );

    return redirect()->to(base_url('keranjang'));
}
public function cart_clear()
{
    $userId = session('id');
    if ($userId) {
        $this->cartModel->clearCart($userId);
    }

    $this->cart->destroy();

    session()->setFlashdata(
        'success',
        'Keranjang berhasil dikosongkan'
    );

    return redirect()->to(base_url('keranjang'));
}
}
