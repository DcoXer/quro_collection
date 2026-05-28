<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiteSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Teks Beranda';

    protected static \UnitEnum|string|null $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Teks Beranda';

    protected string $view = 'filament.pages.site-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([

                        Tab::make('Umum')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Identitas Situs')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nama Situs')
                                            ->placeholder('Quro Collection')
                                            ->columnSpan(1),
                                        TextInput::make('site_tagline')
                                            ->label('Tagline')
                                            ->placeholder('Premium Muslim Fashion')
                                            ->columnSpan(1),
                                    ]),
                                Section::make('Kontak')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('Email')
                                            ->email()
                                            ->placeholder('hello@qurocollection.com')
                                            ->columnSpan(1),
                                        TextInput::make('whatsapp_number')
                                            ->label('Nomor WhatsApp')
                                            ->placeholder('628123456789')
                                            ->helperText('Format internasional tanpa + (contoh: 628123456789)')
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Tab::make('Hero Beranda')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Section::make('Headline Hero')
                                    ->description('Ditampilkan di bagian paling atas halaman beranda.')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('hero_line1')
                                            ->label('Baris 1')
                                            ->placeholder('Tampil')
                                            ->columnSpan(1),
                                        TextInput::make('hero_line2_italic')
                                            ->label('Baris 2 (italic / aksen)')
                                            ->placeholder('Elegan')
                                            ->columnSpan(1),
                                        TextInput::make('hero_line3')
                                            ->label('Baris 3')
                                            ->placeholder('Setiap Hari')
                                            ->columnSpan(1),
                                    ]),
                                Section::make('Subteks Hero')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('hero_subtext_mobile')
                                            ->label('Subteks (Mobile)')
                                            ->placeholder('Baju koko premium, desain modern, nyaman dipakai kapan saja.')
                                            ->columnSpan(1),
                                        TextInput::make('hero_subtext_desktop')
                                            ->label('Subteks (Desktop)')
                                            ->placeholder('Koleksi baju koko premium dengan desain modern dan bahan berkualitas tinggi.')
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Tab::make('Cerita Kami')
                            ->icon('heroicon-o-book-open')
                            ->schema([
                                Section::make('Story Section')
                                    ->description('Ditampilkan di section "Cerita Kami" pada halaman beranda.')
                                    ->schema([
                                        TextInput::make('story_headline')
                                            ->label('Headline')
                                            ->placeholder('Menghadirkan Elegansi dalam Setiap Jahitan')
                                            ->columnSpanFull(),
                                        Textarea::make('story_body_desktop_1')
                                            ->label('Paragraf Pertama (Desktop)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Textarea::make('story_body_desktop_2')
                                            ->label('Paragraf Kedua (Desktop)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Poin Keunggulan (Mobile)')
                                    ->description('Ditampilkan sebagai bullet point di mobile.')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('story_bullet_1')
                                            ->label('Poin 1')
                                            ->placeholder('Bahan premium, nyaman seharian')
                                            ->columnSpan(1),
                                        TextInput::make('story_bullet_2')
                                            ->label('Poin 2')
                                            ->placeholder('Proses & kirim dalam 1×24 jam')
                                            ->columnSpan(1),
                                        TextInput::make('story_bullet_3')
                                            ->label('Poin 3')
                                            ->placeholder('Garansi kepuasan setiap pembelian')
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Tab::make('Nilai Kami')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Section::make('Kartu 1')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('value_1_title')
                                            ->label('Judul')
                                            ->placeholder('Kualitas Premium')
                                            ->columnSpan(1),
                                        TextInput::make('value_1_desc')
                                            ->label('Deskripsi')
                                            ->placeholder('Bahan dipilih dengan ketat untuk kenyamanan dan ketahanan jangka panjang.')
                                            ->columnSpan(1),
                                    ]),
                                Section::make('Kartu 2')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('value_2_title')
                                            ->label('Judul')
                                            ->placeholder('Pengiriman Cepat')
                                            ->columnSpan(1),
                                        TextInput::make('value_2_desc')
                                            ->label('Deskripsi')
                                            ->placeholder('Pesanan diproses dalam 1×24 jam dan dikirim ke seluruh Indonesia.')
                                            ->columnSpan(1),
                                    ]),
                                Section::make('Kartu 3')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('value_3_title')
                                            ->label('Judul')
                                            ->placeholder('Garansi Kepuasan')
                                            ->columnSpan(1),
                                        TextInput::make('value_3_desc')
                                            ->label('Deskripsi')
                                            ->placeholder('Kepuasan pelanggan adalah prioritas utama. Garansi untuk setiap pembelian.')
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Tab::make('CTA Beranda')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Section::make('Banner Call-to-Action')
                                    ->description('Ditampilkan di bagian bawah halaman beranda.')
                                    ->schema([
                                        TextInput::make('cta_headline')
                                            ->label('Headline')
                                            ->placeholder('Temukan Koleksi Terbaik untuk Kamu')
                                            ->columnSpanFull(),
                                        TextInput::make('cta_subtext')
                                            ->label('Subteks')
                                            ->placeholder('Lebih dari {jumlah} produk siap menemani penampilan elegan kamu sehari-hari.')
                                            ->helperText('Gunakan {jumlah} untuk menampilkan jumlah produk secara otomatis.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        SiteSetting::setMany($data);

        Notification::make()
            ->success()
            ->title('Berhasil disimpan!')
            ->body('Perubahan konten telah tersimpan.')
            ->send();
    }
}
