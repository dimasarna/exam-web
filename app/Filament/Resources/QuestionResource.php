<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('exam_id')
                    ->relationship('exam', 'title')
                    ->required(),
                    
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exam.title')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('text')
                    ->html()
                    ->limit(50)
                    ->tooltip(fn ($record) => strip_tags($record->text))
                    ->searchable(),
                    
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
                Tables\Filters\SelectFilter::make('exam')
                    ->relationship('exam', 'title'),
                    
                Tables\Filters\SelectFilter::make('question_type')
                    ->options([
                        'multiple_choice' => 'Pilihan Ganda',
                        'true_false' => 'Benar Salah',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->role_id === Role::IS_ADMINISTRATOR) {
                    return $query;
                }
                
                return $query->whereHas('exam.classroom.users', function($q) {
                    $q->where('role_id', Role::IS_PENGAJAR)
                      ->where('user_id', auth()->id());
                });
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnswerOptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
