<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpdateLogResource\Pages;
use App\Filament\Resources\UpdateLogResource\RelationManagers;
use App\Models\UpdateLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UpdateLogResource extends Resource
{
    protected static ?string $model = UpdateLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function getNavigationGroup(): ?string
    {
        return 'Logs';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time'),

                Tables\Columns\TextColumn::make('action'),

                Tables\Columns\TextColumn::make('response')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUpdateLogs::route('/'),
            'create' => Pages\CreateUpdateLog::route('/create'),
            'edit' => Pages\EditUpdateLog::route('/{record}/edit'),
        ];
    }
}
