<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnswerOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'answerOptions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                
                Forms\Components\TextInput::make('order_position')
                    ->numeric()
                    ->default(0)
                    ->columnSpanFull(),
                    
                Forms\Components\Toggle::make('is_correct')
                    ->required()
                    ->default(false),
                    
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
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->text),
                    
                Tables\Columns\IconColumn::make('is_correct')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('order_position'),
            ])
            ->filters([
                //
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
            ->modifyQueryUsing(function ($query) {
                return $query->orderBy('order_position');
            });
    }
}
