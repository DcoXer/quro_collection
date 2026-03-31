<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn($state, $set) =>
                        $set('slug', \Illuminate\Support\Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Textarea::make('description')->columnSpanFull(),

                TextInput::make('price')
                    ->label('Harga Base')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->helperText('Harga default untuk semua size. Bisa di-override per size di bawah.'),

                FileUpload::make('image')
                    ->label('Foto Utama (thumbnail)')
                    ->image()
                    ->disk('public')
                    ->directory('products'),

                Toggle::make('is_active')->required(),
                Section::make('SEO')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->placeholder('Kosongkan untuk pakai nama produk')
                            ->maxLength(60)
                            ->helperText('Maks 60 karakter'),

                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->placeholder('Kosongkan untuk pakai deskripsi produk')
                            ->maxLength(160)
                            ->helperText('Maks 160 karakter'),
                    ]),

                // Multiple Media (foto + video)
                Repeater::make('media')
                    ->relationship()
                    ->label('Foto & Video Produk (max 5 foto + 1 video)')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('type')
                            ->options([
                                'image' => 'Foto',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->live(),

                        FileUpload::make('path')
                            ->label('File')
                            ->disk('public')
                            ->directory('products/media')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4'])
                            ->maxSize(51200)
                            ->required(),

                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->maxItems(6),

                // Variants — semua size punya stok masing-masing
                Repeater::make('variants')
                    ->relationship()
                    ->label('Ukuran & Stok')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('size')
                            ->options([
                                'S'      => 'S',
                                'M'      => 'M',
                                'L'      => 'L',
                                'XL'     => 'XL',
                                'XXL'    => 'XXL',
                                'XXL+'   => 'XXL+',
                                'XXL++'  => 'XXL++',
                                'XXL+++' => 'XXL+++',
                            ])
                            ->required(),
                        TextInput::make('price')
                            ->label('Harga (opsional)')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('Kosongkan = pakai harga base')
                            ->helperText('Isi jika size ini punya harga berbeda'),
                        TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
