<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table      = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'product_id', 'qty', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getCartByUser($userId)
    {
        return $this->select('cart.*, p.nama, p.harga, p.foto')
            ->join('product p', 'cart.product_id = p.id')
            ->where('cart.user_id', $userId)
            ->findAll();
    }

    public function addOrUpdateItem($userId, $productId, $qty)
    {
        $existing = $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $this->update($existing['id'], [
                'qty'        => $existing['qty'] + $qty,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->insert([
                'user_id'    => $userId,
                'product_id' => $productId,
                'qty'        => $qty,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function updateQty($userId, $productId, $qty)
    {
        $existing = $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $this->update($existing['id'], [
                'qty'        => $qty,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function removeItem($userId, $productId)
    {
        $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearCart($userId)
    {
        $this->where('user_id', $userId)->delete();
    }
}
