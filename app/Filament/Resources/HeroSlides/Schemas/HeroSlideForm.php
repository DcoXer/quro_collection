<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->label('Tipe Media')
                ->options([
                    'image' => 'Gambar (Image)',
                    'video' => 'Video',
                ])
                ->required()
                ->default('image'),

            FileUpload::make('image')
                ->label('Upload File (Gambar / Video)')
                ->multiple()
                ->disk('public')
                ->directory('hero-slides')
                ->acceptedFileTypes([
                    'image/jpeg', 'image/png', 'image/webp',
                    'video/mp4', 'video/webm', 'video/ogg',
                ])
                ->helperText('Bisa pilih banyak file sekaligus.')
                ->required(),

            Select::make('placement')
                ->label('Halaman')
                ->options([
                    'welcome' => 'Welcome (Halaman Utama)',
                ])
                ->required()
                ->default('welcome'),

            TextInput::make('sort_order')
                ->label('Urutan')
                ->numeric()
                ->default(0)
                ->helperText('Angka lebih kecil tampil lebih awal.'),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
