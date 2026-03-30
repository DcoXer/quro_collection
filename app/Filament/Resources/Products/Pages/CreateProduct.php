<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Notification;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $product = $this->record;

        if (!$product->is_active) {
            return;
        }

        $now = now();
        $rows = User::whereNotNull('email_verified_at')
            ->pluck('id')
            ->map(fn ($userId) => [
                'user_id'    => $userId,
                'type'       => 'new_product',
                'title'      => 'Produk Baru Tersedia!',
                'message'    => $product->name . ' baru saja hadir di Quro Collection. Cek sekarang!',
                'url'        => route('shop.show', $product->slug),
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

        foreach (array_chunk($rows, 500) as $chunk) {
            Notification::insert($chunk);
        }
    }
}
