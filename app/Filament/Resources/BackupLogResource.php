<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BackupLogResource\Pages;
use App\Filament\Resources\BackupLogResource\RelationManagers;
use App\Models\BackupLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BackupLogResource extends Resource
{
    protected static ?string $model = BackupLog::class;

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

                Tables\Columns\TextColumn::make('path')
                    ->url(function (BackupLog $record) {
                        $path = str_replace('B2B/', '', $record->path);

                        return route('download-backup', $path);
                    }),

                Tables\Columns\TextColumn::make('response')
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListBackupLogs::route('/'),
            'create' => Pages\CreateBackupLog::route('/create'),
            'edit' => Pages\EditBackupLog::route('/{record}/edit'),
        ];
    }
}
