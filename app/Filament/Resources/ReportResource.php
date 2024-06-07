<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function getNavigationGroup(): ?string
    {
        return __('lunarpanel::global.sections.sales');
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
                Tables\Columns\TextColumn::make('order.created_at')
                    ->date()
                    ->sortable()
                    ->label('Date'),

                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.reference')
                    ->label('Invoice No'),

                Tables\Columns\TextColumn::make('product_code'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer Name'),

                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(function ($state) {
                        $myr = number_format($state / 100, 2);

                        return __("$myr");
                    })
                    ->label('Checkout Amount (RM)'),

                Tables\Columns\TextColumn::make('upline_1')
                    ->label('Kindergarten'),

                Tables\Columns\TextColumn::make('upline_2')
                    ->label('Dealer'),

                Tables\Columns\TextColumn::make('upline_3')
                    ->label('Sales'),

                Tables\Columns\TextColumn::make('kg_commission')
                    ->formatStateUsing(function ($state) {
                        $myr = number_format($state, 2);

                        return __("$myr");
                    })
                    ->label('Kindergarten Commission (RM)'),

                Tables\Columns\TextColumn::make('dealer_commission')
                    ->formatStateUsing(function ($state) {
                        $myr = number_format($state, 2);

                        return __("$myr");
                    })
                    ->label('Dealer Commission (RM)'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                TernaryFilter::make('upline_1')
                    ->label('Has Kindergarten')
                    ->nullable()
                    ->trueLabel('Yes')
                    ->falseLabel('No'),

                TernaryFilter::make('upline_2')
                    ->label('Has Dealer')
                    ->nullable()
                    ->trueLabel('Yes')
                    ->falseLabel('No'),

                TernaryFilter::make('upline_3')
                    ->label('Has Sale')
                    ->nullable()
                    ->trueLabel('Yes')
                    ->falseLabel('No'),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from '.Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until '.Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),

            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label('Export All'),
            ])
            ->actions([
                ExportAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
//            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
