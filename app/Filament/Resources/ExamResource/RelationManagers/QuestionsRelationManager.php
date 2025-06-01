<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_type')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar Salah',
                    ])
                    ->required(),
                    
                Forms\Components\RichEditor::make('text')
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('points')
                    ->numeric()
                    ->default(1)
                    ->required(),
                    
                Forms\Components\TextInput::make('order_position')
                    ->numeric()
                    ->default(0),
                    
                Forms\Components\Textarea::make('explanation')
                    ->columnSpanFull()
                    ->nullable(),
                
                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('text')
            ->columns([
                Tables\Columns\TextColumn::make('text')
                    ->html()
                    ->limit(50)
                    ->tooltip(fn ($record) => strip_tags($record->text)),
                    
                Tables\Columns\TextColumn::make('question_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) : string => match ($state) {
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar Salah',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'multiple_choice' => 'info',
                        'true_false' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('points'),
                
                Tables\Columns\TextColumn::make('order_position')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('question_type')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar Salah',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderBy('order_position');
            });
    }
}
