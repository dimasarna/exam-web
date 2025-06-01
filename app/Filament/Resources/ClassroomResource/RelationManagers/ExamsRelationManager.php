<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamsRelationManager extends RelationManager
{
    protected static string $relationship = 'exams';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_id')
                    ->relationship('classroom', 'name')
                    ->required(),
                    
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->nullable(),
                
                Forms\Components\TextInput::make('total_questions')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('duration_minutes')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('passing_score')
                    ->numeric()
                    ->step(0.01)
                    ->nullable(),
                    
                Forms\Components\TextInput::make('max_attempts')
                    ->numeric()
                    ->default(1)
                    ->required(),
                    
                Forms\Components\Toggle::make('shuffle_questions')
                    ->inline(false)
                    ->default(false)
                    ->required(),
                
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->default(true)
                    ->required(),
                
                Forms\Components\DateTimePicker::make('available_from')
                    ->nullable(),
                    
                Forms\Components\DateTimePicker::make('available_to')
                    ->nullable(),
                    
                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id())
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
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
                Tables\Filters\TernaryFilter::make('is_active'),
                
                Tables\Filters\Filter::make('active_exams')
                    ->label('Currently Available')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('is_active', true)
                        ->where('available_from', '<=', now())
                        ->where('available_to', '>=', now())),
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
                if (auth()->user()->role_id === Role::IS_ADMINISTRATOR) {
                    return $query;
                }

                return $query->whereHas('classroom.users', function($q) {
                    $q->where('user_id', auth()->id());
                });
            });
    }
}
