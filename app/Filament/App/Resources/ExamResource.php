<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Pages\TakeExam;
use App\Filament\App\Resources\ExamResource\Pages;
use App\Filament\App\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
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
                Tables\Columns\TextColumn::make('classroom.name')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('total_questions'),
                
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->formatStateUsing(fn ($state) => "{$state} mins"),
                
                Tables\Columns\TextColumn::make('passing_score'),
                
                Tables\Columns\TextColumn::make('max_attempts'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('available_from')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('available_to')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classroom')
                    ->relationship('classroom', 'name'),
                    
                Tables\Filters\TernaryFilter::make('is_active'),
                
                Tables\Filters\Filter::make('active_exams')
                    ->label('Currently Available')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('is_active', true)
                        ->where('available_from', '<=', now())
                        ->where('available_to', '>=', now())),
            ])
            ->actions([
                Tables\Actions\Action::make('take')
                    ->label('Take exam')
                    ->action(fn (Exam $record) => redirect(self::getUrl('take', ['record' => $record])))
                    ->requiresConfirmation()
                    ->modalIcon()
                    ->modalDescription()
                    ->modalHeading('Memulai Ujian')
                    ->modalContent(fn (Exam $record) => view(
                        'filament.app.resources.exam-resource.pages.actions.take-exam',
                        ['record' => $record]
                    ))
                    ->modalSubmitActionLabel(fn (Exam $record) => $record->attempts->where('user_id', auth()->id())->count() > 0 ? 'Review Ujian' : 'Mulai Ujian')
                    ->modalAlignment(Alignment::Start)
                    ->visible(fn (Exam $record) => $record->isAvailable()),
            ])
            ->bulkActions([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->role_id == Role::IS_ADMINISTRATOR) {
                    return $query;
                }

                return $query->whereHas('classroom.users', function($q) {
                    $q->where('user_id', auth()->id());
                });
            });
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
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
            'take' => Pages\TakeExam::route('/{record}/take'),
        ];
    }
}
