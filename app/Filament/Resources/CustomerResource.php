<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Customers';
    
    protected static ?string $pluralLabel = 'Customers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter full name'),
                        
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->placeholder('example@email.com'),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),

                        DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->placeholder('Select date')
                            ->required(),
                        
                        FileUpload::make('image')
                            ->image()
                            ->directory('customers') // Menyimpan di storage/app/public/customers
                            ->disk('public')
                            ->required(),

                        Textarea::make('address')
                            ->rows(3)
                            ->placeholder('Enter address'),
                        
                        Toggle::make('verified')
                            ->label('Verified Customer'),
                        
                        RichEditor::make('notes')
                            ->label('Additional Notes')
                            ->placeholder('Write some notes about this customer...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->size(50), // Thumbnail size
    
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
    
                TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date()
                    ->sortable(),
    
                BooleanColumn::make('verified') // Menggunakan BooleanColumn untuk kolom boolean
                    ->label('Verified'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make()->visible(fn ($record) => $record->trashed()), // Hanya muncul jika soft deleted
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    

    public static function getRelations(): array
    {
        return [
            // Bisa tambahkan relasi di sini, misalnya CustomerOrdersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
