<?php

namespace App\Filament\Resources\ExitRecords\Tables;

use App\ExitStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExitRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('exit_at')
                    ->label('Keluar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('employee.name')
                    ->label('Pegawai')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('employee.nip')
                    ->label('NIP')
                    ->searchable(),

                TextColumn::make('employee.department.name')
                    ->label('Departemen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('purpose')
                    ->label('Keperluan')
                    ->limit(25)
                    ->tooltip(
                        fn (TextColumn $column): ?string =>
                            $column->getState()
                    ),

                TextColumn::make('destination')
                    ->label('Tujuan')
                    ->limit(20)
                    ->tooltip(
                        fn (TextColumn $column): ?string =>
                            $column->getState()
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (ExitStatus $state): string => match ($state) {
                            ExitStatus::OUT => 'Sedang Keluar',
                            ExitStatus::RETURNED => 'Sudah Kembali',
                        }
                    )
                    ->color(
                        fn (ExitStatus $state): string => match ($state) {
                            ExitStatus::OUT => 'warning',
                            ExitStatus::RETURNED => 'success',
                        }
                    ),

                TextColumn::make('return_at')
                    ->label('Kembali')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('Durasi')
                    ->state(function ($record): string {
                        $minutes = $record->getDurationInMinutes();

                        if ($minutes === null) {
                            return '—';
                        }

                        if ($minutes === 0) {
                            return '< 1 menit';
                        }

                        return self::formatDuration($minutes);
                    }),

                TextColumn::make('recorder.name')
                    ->label('Dicatat Oleh')
                    ->searchable()
                    ->sortable(),
            ])

            ->filters([

                /*
                 * Filter periode tanggal keluar.
                 */
                Filter::make('exit_date')
                    ->label('Periode Keluar')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('until')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->columns(2)
                    ->query(function (
                        Builder $query,
                        array $data,
                    ): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->whereDate('exit_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->whereDate('exit_at', '<=', $date)
                            );
                    }),

                /*
                 * Filter status.
                 */
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        ExitStatus::OUT->value => 'Sedang Keluar',
                        ExitStatus::RETURNED->value => 'Sudah Kembali',
                    ]),

                /*
                 * Filter departemen.
                 */
                SelectFilter::make('department')
                    ->label('Departemen')
                    ->relationship(
                        'employee.department',
                        'name',
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->recordUrl(
                fn ($record): string =>
                    \App\Filament\Resources\ExitRecords\ExitRecordResource::getUrl(
                        'view',
                        ['record' => $record],
                    )
            )

            ->defaultSort('exit_at', 'desc')

            ->recordActions([])

            ->toolbarActions([]);
    }

    protected static function formatDuration(int $minutes): string
    {
        if ($minutes < 60) {
            return "{$minutes} menit";
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return "{$hours} jam";
        }

        return "{$hours} jam {$remainingMinutes} menit";
    }
}