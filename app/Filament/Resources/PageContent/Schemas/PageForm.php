<?php

namespace App\Filament\Resources\PageContent\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Konten Halaman')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul / Headline')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Isi Konten')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3',
                            'bulletList', 'orderedList',
                            'blockquote',
                            'link',
                            'undo', 'redo',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('SEO (Opsional)')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(60)
                        ->helperText('Biarkan kosong untuk menggunakan judul halaman.')
                        ->columnSpanFull(),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(2)
                        ->maxLength(160)
                        ->columnSpanFull(),
                ]),

            Section::make('Info')
                ->schema([
                    TextInput::make('slug')
                        ->label('Slug')
                        ->disabled()
                        ->helperText('Slug tidak bisa diubah — ini menentukan URL halaman.')
                        ->columnSpanFull(),
                ]),

        ]);
    }
}
