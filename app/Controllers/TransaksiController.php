<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel; 
use App\Models\TransactionDetailModel; 

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client; 
    protected $apiKey;
    protected $transaction; 
    protected $transaction_detail; 

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->client = new \GuzzleHttp\Client(); 
        $this->apiKey = env('COST_KEY');
        $this->transaction=new TransactionModel(); 
        $this->transaction_detail=new TransactionDetailModel(); 
    }

    public function index()
    {  
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
        $qty = $this->request->getPost('qty' . $i++);

        $this->cart->update([
            'rowid' => $item['rowid'],
            'qty'   => $qty
        ]);
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

public function cart_delete($rowid)
{
    $this->cart->remove($rowid);

    session()->setFlashdata(
        'success',
        'Produk berhasil dihapus dari keranjang'
    );

    return redirect()->to(base_url('keranjang'));
}
public function cart_clear()
{
    $this->cart->destroy();

    session()->setFlashdata(
        'success',
        'Keranjang berhasil dikosongkan'
    );

    return redirect()->to(base_url('keranjang'));
}
}
