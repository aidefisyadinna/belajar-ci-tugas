<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class Home extends BaseController
{
    protected $productModel;
    protected $transaction;
    protected $transaction_detail;

    function __construct(){
        helper(['number', 'form']);
        $this->productModel = new ProductModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }
    public function search()
    {
        $query = $this->request->getGet('query');
        if (!$query) {
            return redirect()->to('/');
        }
        $products = $this->productModel
            ->like('nama', $query)
            ->findAll();
        return view('v_home', ['products' => $products]);
    }

    public function index()
    {
            return view('v_home', [
                'products' => $this->productModel->findAll()
            ]);
    }

    public function profile()
    {
        $username = session()->get('username');
        $data['username'] = $username;
        $buy = $this->transaction->where('username', $username)->findAll();
        $data['buy'] = $buy;
        $product = [];
        if (!empty($buy)) {
            foreach ($buy as $item) {
                $detail = $this->transaction_detail
                    ->select('transaction_detail.*, product.nama, product.harga, product.foto')
                    ->join('product', 'transaction_detail.product_id=product.id')
                    ->where('transaction_id', $item['id'])
                    ->findAll();
                if (!empty($detail)) {
                    $product[$item['id']] = $detail;
                }
            }
        }
        $data['product'] = $product;
        return view('v_profile', $data);
    }

    public function penjualan()
    {
        $data['transactions'] = $this->transaction->findAll();
        return view('v_penjualan', $data);
    }
}
