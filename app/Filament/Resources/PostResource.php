<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->rules([
                        'min:3',
                        'max:50'
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (\Closure $set, $state) {
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')->required(),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->rules([
                        'min:3',
                        'max:500'
                    ]),
                Forms\Components\CheckboxList::make('categories')
                    ->relationship('categories', 'name'),
                Forms\Components\Repeater::make('comments')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('user')
                            ->relationship('user', 'name'),
                        Forms\Components\Textarea::make('body')
                            ->rules(
                                'min:3',
                                'max:200',
                            )
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->searchable()
                    ->label('Add date'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->searchable()
                    ->label('Last edit date'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
